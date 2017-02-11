from task import task
from fabric.api import env


@task(environments=['local'])
def generate(populate='false'):
    "Generate database"
    env.compose_run('php bin/console doctrine:database:drop --force ', 'php')
    env.compose_run('php bin/console doctrine:database:create ', 'php')
    env.compose_run('php bin/console doctrine:schema:update --force ', 'php')
    if populate == 'true':
        env.compose_run('php bin/console hautelook:fixtures:load -n ', 'php')

@task(environments=['local'])
def populate():
    "Populate database with fixtures"
    env.compose_run('php bin/console hautelook:fixtures:load -n ', 'php')