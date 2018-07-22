from fabric.api import task, env
from fabric.operations import local, _shell_escape
from fabric.context_managers import quiet
import os
from sys import platform

@task
def up():
    """
    Ensure infrastructure is synced and running
    """
    docker_compose('build')
    docker_compose('up --remove-orphans -d')


@task
def clean():
    """
    Clean the infrastructure, remove all data
    """
    docker_compose('rm -f -v')


@task
def cache_clear():
    """
    Clear cache of the application
    """
    docker_compose_run('rm -rf var/cache/', 'php', 'mycellar', no_deps=True)


@task
def start():
    """
    Ensure everything is started and installed
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
    clean()
    docker_compose('build --no-cache')

    if not os.path.exists('./.env'):
        local('cp .env.dist .env')

    docker_compose('up --remove-orphans -d --force-recreate')
    cache_clear()
    install()


@task
def restart_service(service):
    """
    Restart a single service
    """
    docker_compose('restart %s' % (service))


@task
def stop():
    """
    Stop the infrastructure
    """
    docker_compose('stop')


@task
def reboot():
    """
    Reboot the infrastructure
    """
    stop()
    start()


@task
def logs():
    """
    Show logs for all container
    """
    docker_compose('logs -f --tail=150')


@task
def install(environment='dev'):
    """
    Install application (composer, assets)
    """
    docker_compose_run('composer install --no-scripts ' + ('--no-dev', '--dev')[environment == 'dev'], 'php', 'mycellar')
    cache_clear()
    docker_compose_run('php bin/console cache:warmup --no-optional-warmers --env=' + environment, 'php', 'mycellar')
    docker_compose_run('php bin/console assets:install --symlink', 'php', 'mycellar')


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
def drop_db():
    """
    Drop the database
    """
    docker_compose_run('php bin/console doctrine:database:drop --force --if-exists ', 'php', no_deps=True)

@task
def create_db():
    """
    (Re)Create an empty database
    """
    drop_db()
    docker_compose_run('php bin/console doctrine:database:create ', 'php', no_deps=True)


@task
def diff_migration():
    """
    Generate a migration by comparing the current database to the mapping information
    """
    docker_compose_run('php bin/console doctrine:migration:diff', 'php', 'mycellar', no_deps=True)


@task
def migrate():
    """
    Apply available database migrations
    """
    docker_compose_run('php bin/console doctrine:migration:migrate --no-interaction', 'php', 'mycellar', no_deps=True)


@task
def update_db():
    """
    Update database to match schema
    """
    docker_compose_run('php bin/console doctrine:schema:update --force', 'php', 'mycellar', no_deps=True)


@task
def populate_db():
    """
    Import fixtures into database
    """
    docker_compose_run('php bin/console doctrine:fixtures:load --no-interaction', 'php', 'mycellar', no_deps=True)


@task
def init_db(populate='true'):
    """
    Drop and recreate database with updated schema then load fixtures if specified so
    """
    create_db()
    migrate()
    if populate == 'true':
        populate_db()


@task
def import_csv(purge='true',path='false',mapping='false'):
    """
    Import data from csv file, use option purge to truncate the wine and bottle tables before. You can specify the csv file path (in csv format) and the mapping file path (in yaml format).
    """
    docker_compose_run('php bin/console mycellar:import -n' + ('', ' --purge')[purge == 'true'] + ('', ' %s' % path)[path != 'false'] + ('', ' %s' % mapping)[mapping != 'false'], 'php', 'mycellar', no_deps=True)


@task
def ssh():
    """
    Ssh into the application container
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
