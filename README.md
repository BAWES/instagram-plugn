# Plugn.io Project - Instagram Comment Management

## Installation Instructions

After cloning the project for usage you need to do the following:

### 1 - Install MySQL and Import SQL file provided in main folder

File name: `plugn.mssql`

### 2 - Install Redis and configure for session storage

Procedure to install redis depends on environment you're installing on.
Redis is currently being used for both Session Storage and Cache. Feel free
to change your local environment configs to use file storage instead.

### 3 - Initialize Environment + Install App Migrations

Run the following commands:

Initialize your Environment via `./yii init`

Install DB migrations via `./yii migrate`

Get the latest dependencies via
`composer install`
