# Plugn.io Project - Instagram Comment Management

## Installation Instructions

After cloning the project for usage you need to do the following:

### 1 - Import SQL file provided in main folder

File name: `plugn.mssql`

### 2 - Initialize Environment

Run the following commands:

`./yii init`

`./yii migrate`

### 3 - Install External Migrations

#### User Module

https://github.com/dektrium/yii2-user

`./yii migrate/up --migrationPath=@vendor/dektrium/yii2-user/migrations`
