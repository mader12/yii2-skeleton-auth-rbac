#!/bin/bash

php yii migrate --migrationPath=@yii/rbac/migrations

php yii migrate

php yii rbac/init
