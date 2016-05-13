# Plugn.io Project - Instagram Comment Management

## Installation Instructions

After cloning the project for usage you need to do the following:

### 1 - Install MySQL and Import SQL file provided in main folder

File name: `plugn.mssql`

### 2 - Install Redis and configure for session storage

Procedure to install redis depends on environment you're installing on.

### 3 - Initialize Environment + Install App Migrations

Run the following commands:

`./yii init`

`./yii migrate`

### 4 - Install External Migrations

#### User Module

https://github.com/dektrium/yii2-user

`./yii migrate/up --migrationPath=@vendor/dektrium/yii2-user/migrations`
