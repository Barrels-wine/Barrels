from __future__ import with_statement
from infrastructure.deploylib import environments
from infrastructure.deploylib.task import task
from infrastructure.deploylib import infrastructure, app, database
from fabric.api import local

@task
def start():
    """
    Be sure that everything is started and installed
    :return:
    """
    infrastructure.build()
    infrastructure.up()
    app.install()


@task
def stop():
    infrastructure.stop()
