# Orbit (Online Referal Booking Information Tool)

## Background

There are more than 500 legal services across Victoria and a web of eligibility questions determining if a client qualifies for a service. Depending on the client’s circumstances, type of legal matter(s), what stage the legal matter is in and where they live, work or study they either get assisted by the service they approach or get a referral to another service.
It has been widely recognised that finding appropriate legal services for clients across the legal assistance sector (VLA and CLCs) as well as getting them into those services has been less than perfect for a long time.

ORBIT aims to tackle some of the challenges staff and clients meet when a legal problem occurs while focusing on improving both the client and staff experience.

## What is ORBIT

ORBIT is an online, referral, booking and information tool that helps staff and volunteers make accurate referrals across the legal assistance sector in Victoria. The tool has catalogued all VLA front-line services, the vast majority of CLC services and common non-legal services. It has the functionality to be an aid to triage, to guide inexperienced and experienced users alike to interrogate a legal matter and client circumstances appropriately.

Staff and volunteers can use the tool from their web browser to match people with the best service based on location, eligibility and problem type. They can easily share information about the referral to the client by SMS and email.
For VLA offices it is possible to share appointment availability and have other VLA offices book clients directly into a centralised calendar.

## Technical information

ORBIT is being developed between Code for Australia and the ICT team of VLA. In order to get the most of the current of both teams ICT has built all the interaction between model and the business logic providing a SOAP API supporting different functionalities such as bookings, referrals or management of services.

On the other hand, CFA fellows have built a web application through researching and approaching potential users of Orbit. This application was built using PHP (Laravel 5.4), Javascript (jQuery) and MySql (MariaDB). In order to keep the front-end development time short, the team acquired a license of the "Metronic" Bootstrap admin dashboard.

# Installation

## Git clone

`git clone https://github.com/CodeforAustralia/vla-orbit.git`

## Setup Laravel Project

Generate dependencies (Vendor folder, autoload, etc...)
> `composer install`

Create .env variables from the step `Setup Laravel .env variables` in this tutorial [here](https://github.com/CodeforAustralia/vla-orbit#setup-laravel-env-variables)

Generate your own project key
> `artisan key:generate`

Install Laravel Mix
> `npm install laravel-mix --save-dev`

If you want to compile files just run, just remenber to compilem according to the environment that you are working at.
> `npm run development`

### Setup Laravel .env variables

In order to keep configuration values centralized all of those values should be stores inside a .env file located on the root folder of your project.

The values that are relevant for us are:

*Basic Laravel settings*

* `APP_NAME=Orbit`
* `APP_ENV=local`
* `APP_KEY=` Your own laravel application key here is an example on how to get it https://laravel.com/docs/5.4/encryption#configuration
* `APP_DEBUG=` This should be only true when you are debuggin your application ie. your development server.
* `APP_LOG_LEVEL=` Log Severity Levels one of [debug, info, notice, warning, error, critical, alert, emergency]
* `APP_URL=http://localhost`
* `APP_TEAM_EMAIL=team@yourdomain.com` This is used to receive feedback or support from users

* `DB_CONNECTION=` Type of connection that you will use with your database ie. mysql
* `DB_HOST=` Address that will be used to connect with your database ie. 127.0.0.1
* `DB_PORT=` Port that will be used to connect with your database ie. 3306
* `DB_DATABASE=` Database name
* `DB_USERNAME=` User with privilege to access your database
* `DB_PASSWORD=` User's password to access your database

*SMTP settings*

* `MAIL_FROM_ADDRESS_CLC=` Email address to send emails to CLC users
* `MAIL_FROM_ADDRESS=`  Email address to send emails to VLA users
* `MAIL_FROM_NAME='Orbit'`
* `MAIL_DRIVER=smtp`
* `MAIL_HOST=` Address of your smtp server
* `MAIL_PORT=` Port of your smtp server
* `MAIL_USERNAME=` User of your smtp server
* `MAIL_PASSWORD=` Password of your smtp server
* `MAIL_ENCRYPTION=` Encryption used by your smtp server

*Simple SAML settings, to authenticate users using VLA's Active Directory*

* `SIMPLESML_SP=` Simple SAML identifier for Active Directory Auth

*Web Services settings, this web services are hosted in a different server and each WSDL is accessed by Laravel through SOAP*

* `ORBIT_WDSL_URL=` Core SOAP address
* `ORBIT_BOOKING_WDSL_URL=` Bookings SOAP address
* `ORBIT_NO_REPLY_EMAILS_WDSL_URL=` No Reply Emails SOAP address

*Other libraries*

* `GOOGLE_MAPS_KEY=` Your own google maps API KEY with this liraries enabled [Places API, Geocoding API, Maps JavaScript API, Distance Matrix API]
* `TYTINYMCE_KEY=` You should get this key from their Tiny MCE website in order to use it on the project

*As a good practice your .env file should not be committed to your application's source control*

### EER Model

![Orbit EER](https://github.com/CodeforAustralia/vla-orbit/blob/master/public/assets/global/img/Orbit%20EER.png "Orbit EER")

