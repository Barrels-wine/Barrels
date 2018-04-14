from .task import task
from fabric.api import env
import os


@task
def install(environment='dev'):
    "Install all necessary things"
    env.compose_run('composer install --no-scripts ' + ('--no-dev', '--dev')[environment == 'dev'], 'php')
    clear_cache('dev')
    clear_cache('prod')
    clear_cache('test')
    env.compose_run('php bin/console cache:warmup --no-optional-warmers --env=' + environment, 'php')
    env.compose_run('php bin/console assets:install --symlink', 'php')


@task
def clear_cache(environment='dev'):
    "Clear cache"
    env.compose_run('rm -rf var/cache/' + environment, 'php')

@task
def ssh():
    "SSH into php container"
    env.ssh_into('php')