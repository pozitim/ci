#!/bin/bash
docker pull centos
docker pull mysql
docker pull memcached
docker pull redis
docker pull mongo
docker pull pataquest/gearmand
docker build -t pozitim-ci/centos-php54 centos-php54/
docker build -t pozitim-ci/centos-php55 centos-php55/
docker build -t pozitim-ci/centos-php56 centos-php56/