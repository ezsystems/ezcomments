#?ini charset="utf-8"? 

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
DefaultEmbededSortOrder=asc

#Set if the subscriptions will be delete of one subscriber's all the comments on one content have been deleted
DeleteSubscriptionAfterDeleteComment=true

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

#Max length of comment shown in email
CommentMailLength=200

#Make all the updated comment in one notification
CommentsInOne=false

#Number per cronjob executation
NotificationNumberPerExecuation=1

DefaultSortField=modified
DefaultSortOrder=desc


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