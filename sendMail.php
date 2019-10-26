#!/bin/bash

SMTPFROM=njitit490@gmail.com
SMTPTO=ars66@njit.edu
SMTPSERVER=smtp.googlemail.com:587
SMTPUSER=njitit490
SMTPPASS=NJITIT490
MESSAGEBODY="What up man, this is working just fine"
SUBJECT="THIS IS A TEST."

sendMail -f $SMTPFROM -t $SMTPTO -u $SUBJECT -m $MESSAGEBODY -s $SMTPSERVER -xu $SMTPUSER -xp $SMTPPASS
