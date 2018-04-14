from fabric.api import task, env
from fabric.operations import local, _shell_escape
from fabric.context_managers import quiet
import os
from sys import platform


@task
def start():
    """
    Be sure that everything is started and installed
    """
    if not os.path.exists('./.env'):
        local('cp .env.dist .env')

    up()
    cache_clear()
    install()

@task
def clean_start():
    """
    Start everything from fresh
    """
    docker_compose('rm')
    docker_compose('build --no-cache')
    docker_compose('up --remove-orphans -d --force-recreate')


@task
def restart_service(service):
    """
    Restart a single service
    """
    docker_compose('restart %s' % (service))


@task
def up():
    """
    Ensure infrastructure is sync and running
    """
    docker_compose('build')
    docker_compose('up --remove-orphans -d')


@task
def stop():
    """
    Stop the infrastructure
    """
    docker_compose('stop')


@task
def logs():
    """
    Show logs of infrastructure
    """
    docker_compose('logs -f --tail=150')


@task
def install():
    """
    Install application (composer, assets)
    """
    docker_compose_run('composer install', 'php', 'mycellar')


@task
def cs_fix(dry_run=False):
    """
    Fix coding standards in code
    """
    if dry_run:
        docker_compose_run('php-cs-fixer fix --config=.php_cs --dry-run --diff', 'php', 'mycellar')
    else:
        docker_compose_run('php-cs-fixer fix --config=.php_cs', 'php', 'mycellar')


@task
def cache_clear():
    """
    Clear cache of the application
    """
    docker_compose_run('rm -rf var/cache/', 'php', 'mycellar', no_deps=True)


@task
def migrate_database():
    """
    Update database schema
    """
    docker_compose_run('php bin/console doctrine:migration:migrate --no-interaction', 'php', 'mycellar', no_deps=True)


@task
def fixtures():
    """
    Import fixtures into database
    """
    docker_compose_run('php bin/console doctrine:fixtures:load', 'php', 'mycellar', no_deps=True)


@task
def ssh():
    """
    Ssh into the php container
    """
    docker_compose('exec --user=mycellar --index=1 php /bin/bash')


def docker_compose(command_name):
    local('MYCELLAR_UID=%s docker-compose -p mycellar %s %s' % (
        env.uid,
        ' '.join('-f infrastructure/orchestration/' + file for file in env.compose_files),
        command_name
    ))


def docker_compose_run(command_name, service, user="mycellar", no_deps=False):
    args = [
        'run '
        '--rm '
        '-u %s ' % _shell_escape(user)
    ]

    if no_deps:
        args.append('--no-deps ')

    docker_compose('%s %s /bin/bash -c "%s"' % (
        ' '.join(args),
        _shell_escape(service),
        _shell_escape(command_name)
    ))


def set_local_configuration():
    env.compose_files = ['base.yml']
    env.uid = int(local('id -u', capture=True))
    env.root_dir = os.path.dirname(os.path.abspath(__file__))

    if env.uid > 256000:
        env.uid = 1000


set_local_configuration()