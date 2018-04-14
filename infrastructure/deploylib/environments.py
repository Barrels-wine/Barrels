from fabric.api import env, cd
from functools import wraps
from fabric.operations import local as lrun, run, settings
from sys import platform as _platform
from yaml import load, dump

import os

environments = {}


def environnment(config):
    @wraps(config)
    def inner():
        env.environment = config.__name__
        return config()
    environments[config.__name__] = inner
    return inner


@environnment
def local():
    env.run = lrun
    env.local = True
    env.local_ip = '127.0.0.1'
    env.host_string = 'docker@localhost'
    env.compose_files = ['infrastructure/environments/base.yml']
    env.shell = "/bin/sh -c"
    env.directory = env.root_dir
    env.env_file = 'development.env'


def ssh_into(service):
    env.run('docker exec -t -i %s_%s_1 /bin/bash' % (env.project_name, service))


def compose_run(command_name, service, directory=".", user="root", no_deps=False):
    if no_deps:
        env.compose('run --no-deps -u %s %s /bin/bash -c "cd %s && /bin/bash -c \\"%s\\""' % (user, service, directory, command_name))
    else:
        env.compose('run -u %s %s /bin/bash -c "cd %s && %s"' % (user, service, directory, command_name))


def compose(command_name):
    merge_infra_files()
    env.run('cp %s/infrastructure/environments/%s %s/infrastructure/environments/current.env' % (env.directory, env.env_file, env.directory))
    with cd(env.directory):
        env.run('docker-compose -p %s -f %s/%s %s' % (env.project_name, env.directory, env.temporary_file, command_name))


def merge_infra_files():
    output = None
    print(env.compose_files)

    for file in env.compose_files:
        stream = open(env.root_dir + '/' + file, 'r')

        if output is None:
            output = load(stream)
        else:
            output = merge(load(stream), output)

        stream.close()

    outputStream = open(env.root_dir + '/' + env.temporary_file, 'w')
    dump(output, outputStream)
    outputStream.close()

    if not env.local:
        put(env.root_dir + '/' + env.temporary_file, env.directory + '/' + env.temporary_file)


def merge(user, default):
    if isinstance(user, dict) and isinstance(default, dict):
        for k, v in default.iteritems():
            if k not in user:
                user[k] = v
            else:
                user[k] = merge(user[k], v)
    return user

env.directory = '/home/mycellar'
env.docker = True
env.compose = compose
env.compose_run = compose_run
env.project_name = 'mycellar'
env.ssh_into = ssh_into
env.local = False
env.build_at_up = True
env.root_dir = os.path.realpath(os.path.dirname(os.path.realpath(__file__)) + '/../..')
env.compose_files = []
env.temporary_file = 'infrastructure/environments/current.yml'