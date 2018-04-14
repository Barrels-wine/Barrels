from .task import task
from fabric.api import env

@task(environments=['local'])
def init(populate='false'):
    "Init empty database"
    env.compose_run('php bin/console doctrine:database:drop --force ', 'php')
    env.compose_run('php bin/console doctrine:database:create ', 'php')


@task(environments=['local'])
def populate():
    "Populate database with fixtures"
    env.compose_run('php bin/console doctrine:fixtures:load ', 'php')


@task(environments=['local'])
def generate(populate='false'):
    "Generate database"
    init()
    migrate()
    if populate == 'true':
        populate()


@task
def diff_migration():
    "Generate migration diff"
    env.compose_run('php bin/console doctrine:migrations:diff', 'php')


@task
def migrate():
    "Generate migration diff"
    env.compose_run('php bin/console doctrine:migrations:migrate --no-interaction', 'php')


@task(environments=['local'])
def import_csv(purge='false',path='false',mapping='false'):
    "Import from csv file"
    env.compose_run('php bin/console mycellar:import -n' + ('', ' --purge')[purge == 'true'] + ('', ' %s' % path)[path != 'false'] + ('', ' %s' % mapping)[mapping != 'false'], 'php')