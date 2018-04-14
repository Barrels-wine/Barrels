from .task import task
from fabric.api import env
import time


@task
def build(use_cache='true'):
    "Build services for infrastructure"
    env.compose('build' + ('', '  --no-cache')[use_cache == 'false'])


@task
def up(force_recreate='false'):
    "Ensure infrastructure is sync and running"
    if env.build_at_up:
        env.compose('build')
    env.compose('up -d' + ('', ' --force-recreate')[force_recreate == 'true'])


@task
def reboot():
    stop()
    time.sleep(5)
    up()


@task
def stop():
    "Stop the infrastructure"
    env.compose('stop')


@task
def clean():
    "Clean the infrastructure, will also remove all data"
    env.compose('rm -f -v')


@task
def ps():
    "Show infrastructure status"
    env.compose('ps')


@task
def logs(containers='', lines=100):
    "Show infrastructure logs"
    env.compose('logs --tail=%s ' % lines + containers)
