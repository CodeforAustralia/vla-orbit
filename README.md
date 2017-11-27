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

### Setup Laravel .env variables

In order to keep configuration values centralized all of those values should be stores inside a .env file located on the root folder of your project.

The values that are relevant for us are:

* Variable and meaning 1
* Variable and meaning 2
* Variable and meaning 3

*As a good practice your .env file should not be committed to your application's source control*

### EER Model

![Orbit EER](https://github.com/CodeforAustralia/vla-orbit/blob/master/public/assets/global/img/Orbit%20EER.png "Orbit EER")

