@component('mail::message')

Hello, 


The office wasn’t able to assist in regards to your matter and referred you to another service.  


Referral details:

- {{ $args['ServiceProviderName'] }}
- {{ $args['ServiceName'] }}
- {{ $args['ServiceTypeName'] }}
- <strong> Address:</strong> {{ $args['Location'] }} if relevant
- <strong>Phone number:</strong> {{ $args['Phone'] }} if relevant
- <strong>URL:</strong> {{ $args['URL'] }}
- {{ $args['Description'] }}


Please, contact the service as per details above. Once you make contact they will assess whether they can help you or not. 

You can also pay a private lawyer to advise you. The Law Institute of Victoria's Legal Referral Service can put you in touch with a lawyer. With a referral letter from the Law Institute, participating law firms will see clients free of charge for up to the first 30 minutes.
If you book an interview, write your questions down beforehand, so you get as much out of the free 30 minutes as possible. 
If you discuss getting further help from the lawyer, make sure you know how much it will cost. 

This email was sent by ORBIT on behalf of {{ $args['ServiceProviderName'] }}. Please do not reply to this email.


@endcomponent