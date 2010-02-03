<?php /* #?ini charset="utf-8"? 

#Settings for ezcomcomments datatype
[GlobalSettings]

#Set default if the commenting is enabled in object attribute
DefaultEnabled=true

#Set default if the comments are shown
DefaultShown=true

#Set default if the notification is selected
EnableNotification=false

#Set the default embeded comment count
DefaultEmbededCount=3

#Set the default embeded comment sort field
DefaultEmbededSortField=created

#Set the default embeded comment sort order
DefaultEmbededSortOrder=desc

#Settings for notification related
[NotificationSettings]
#notification manager class
NotificationManagerClass=ezcomNotificationEmailManager

#-1 means not paging
NumberPerPage=2

#length of subscriber hash string
SubscriberHashStringLength=32

MailContentType=text/html
MailFrom=noreply@ez.no

ActivationMailContentType=text/html

#Make all the updated comment in one notification
CommentsInOne=false

#Number per cronjob executation
NotificationNumberPerExecuation=10

#Expire day of subscription activation, after which if the subscription has not been activated, it will be deleted.
#-1 means never cleaning up. Formats like 0.5(12 hours)/0.25(6 hours) are possible
DaysToCleanupSubscription=20

#Settings for comment list, commenting
[CommentSettings]

#Number of comments per page in comment list, -1 means not paging
NumberPerPage=7

DefaultSortField=created

DefaultSortOrder=asc

#By default if the notified is checked 
DefaultNotified=true

#By default if the subscription is activated, if not, execute the activiation process
SubscriptionActivated=false

[ManagerClasses]
CommentManagerClass=ezcomCommentCommonManager
SubscriberManagerClass=ezcomSubscriptionManager
PermissionClass=ezcomPermission

[FormSettings]
FormHandlerClass=ezcomFormTool

AvailableFields[]
AvailableFields[]=title
AvailableFields[]=name
AvailableFields[]=website
AvailableFields[]=email
AvailableFields[]=comment

[title]
Required=false
Display=true
PostVarName=CommentTitle

[name]
Required=true
Display=true
PostVarName=CommentName

[website]
Required=false
Display=true
PostVarName=CommentWebsite

[email]
Required=true
Display=true
PostVarName=CommentEmail

[comment]
Required=true
Display=true
PostVarName=CommentContent

*/
?>