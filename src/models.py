# This is an auto-generated Django model module.
# You'll have to do the following manually to clean this up:
#   * Rearrange models' order
#   * Make sure each model has one field with primary_key=True
#   * Make sure each ForeignKey and OneToOneField has `on_delete` set to the desired behavior
#   * Remove `managed = False` lines if you wish to allow Django to create, modify, and delete the table
# Feel free to rename the models, but don't rename db_table values or field names.
from django.db import models


class ActivitypubSites(models.Model):
    domain = models.TextField()

    class Meta:
        managed = False
        db_table = 'activitypub_sites'


class ActivitypubUserUrls(models.Model):
    int_id = models.AutoField(primary_key=True)
    user_id = models.IntegerField()
    id = models.TextField()
    featured = models.TextField()
    followers = models.TextField()
    following = models.TextField()
    profile_picture = models.TextField()
    banner_picture = models.TextField()
    inbox = models.TextField()
    outbox = models.TextField()
    last_updated = models.IntegerField()

    class Meta:
        managed = False
        db_table = 'activitypub_user_urls'


class Bans(models.Model):
    autoint = models.AutoField(primary_key=True)
    userid = models.IntegerField()
    reason = models.TextField()
    time = models.BigIntegerField()

    class Meta:
        managed = False
        db_table = 'bans'


class BlacklistedReferer(models.Model):
    url = models.TextField()

    class Meta:
        managed = False
        db_table = 'blacklisted_referer'


class ChannelComments(models.Model):
    comment_id = models.AutoField(primary_key=True)
    id = models.TextField()
    reply_to = models.BigIntegerField()
    comment = models.TextField()
    author = models.BigIntegerField()
    date = models.BigIntegerField()
    deleted = models.IntegerField()

    class Meta:
        managed = False
        db_table = 'channel_comments'


class Comments(models.Model):
    comment_id = models.BigAutoField(primary_key=True)
    id = models.TextField(db_comment='ID to video or user.')
    reply_to = models.BigIntegerField()
    comment = models.TextField(db_comment='The comment itself, formatted in Markdown.')
    author = models.BigIntegerField(db_comment='Numerical ID of comment author.')
    date = models.BigIntegerField(db_comment='UNIX timestamp when the comment was posted.')
    deleted = models.IntegerField(db_comment='States that the comment is deleted')

    class Meta:
        managed = False
        db_table = 'comments'


class DeletedVideos(models.Model):
    autoint = models.AutoField(primary_key=True)
    id = models.CharField(max_length=11)
    uploaded_time = models.BigIntegerField()
    deleted_time = models.BigIntegerField()
    moved_to_bitqobo = models.IntegerField()

    class Meta:
        managed = False
        db_table = 'deleted_videos'


class Favorites(models.Model):
    user_id = models.IntegerField()
    video_id = models.TextField()

    class Meta:
        managed = False
        db_table = 'favorites'


class InviteKeys(models.Model):
    invite_key = models.CharField(max_length=64)
    generated_by = models.IntegerField()
    claimed_by = models.IntegerField(blank=True, null=True)
    generated_time = models.IntegerField()
    claimed_time = models.IntegerField(blank=True, null=True)

    class Meta:
        managed = False
        db_table = 'invite_keys'


class Ipbans(models.Model):
    ip = models.CharField(max_length=45)
    reason = models.CharField(max_length=255)
    time = models.BigIntegerField()

    class Meta:
        managed = False
        db_table = 'ipbans'


class JournalComments(models.Model):
    comment_id = models.AutoField(primary_key=True)
    id = models.TextField()
    reply_to = models.BigIntegerField()
    comment = models.TextField()
    author = models.BigIntegerField()
    date = models.BigIntegerField()
    deleted = models.IntegerField()

    class Meta:
        managed = False
        db_table = 'journal_comments'


class Journals(models.Model):
    title = models.CharField(max_length=128)
    post = models.TextField()
    author = models.IntegerField()
    date = models.IntegerField()
    is_site_news = models.IntegerField()

    class Meta:
        managed = False
        db_table = 'journals'


class Notifications(models.Model):
    type = models.IntegerField()
    level = models.IntegerField(blank=True, null=True)
    recipient = models.IntegerField()
    sender = models.IntegerField()
    timestamp = models.IntegerField()
    related_id = models.IntegerField()

    class Meta:
        managed = False
        db_table = 'notifications'


class Passwordresets(models.Model):
    id = models.CharField(max_length=64)
    user = models.IntegerField()
    time = models.IntegerField()
    active = models.IntegerField()

    class Meta:
        managed = False
        db_table = 'passwordresets'


class Rating(models.Model):
    user = models.PositiveBigIntegerField(db_comment='User that does the rating.')
    video = models.PositiveBigIntegerField(db_comment='Video that is being rated.')
    rating = models.PositiveIntegerField(db_comment='1 for like, 0 for dislike.')

    class Meta:
        managed = False
        db_table = 'rating'


class SiteSettings(models.Model):
    development = models.IntegerField()
    maintenance = models.IntegerField()
    branding_name = models.CharField(max_length=64)
    branding_assets = models.CharField(max_length=128)

    class Meta:
        managed = False
        db_table = 'site_settings'


class Subscriptions(models.Model):
    id = models.IntegerField(db_comment='ID of the user that wants to subscribe to a user.')
    user = models.IntegerField(db_comment='The user that the user wants to subscribe to.')

    class Meta:
        managed = False
        db_table = 'subscriptions'


class Suggestions(models.Model):
    author = models.IntegerField()
    title = models.TextField()
    description = models.TextField()
    time = models.IntegerField()

    class Meta:
        managed = False
        db_table = 'suggestions'


class TagIndex(models.Model):
    video_id = models.IntegerField()
    tag_id = models.IntegerField()

    class Meta:
        managed = False
        db_table = 'tag_index'


class TagMeta(models.Model):
    tag_id = models.AutoField(primary_key=True)
    name = models.TextField()
    latestuse = models.BigIntegerField(db_column='latestUse')  # Field name made lowercase.

    class Meta:
        managed = False
        db_table = 'tag_meta'


class Takedowns(models.Model):
    submission = models.TextField()
    time = models.IntegerField()
    reason = models.TextField()
    sender = models.IntegerField()

    class Meta:
        managed = False
        db_table = 'takedowns'


class UserOldNames(models.Model):
    autoint = models.AutoField(primary_key=True)
    user = models.IntegerField()
    old_name = models.CharField(max_length=128)
    time = models.IntegerField()

    class Meta:
        managed = False
        db_table = 'user_old_names'


class Users(models.Model):
    name = models.CharField(max_length=128, db_comment='Username, chosen by the user')
    email = models.CharField(max_length=128)
    password = models.CharField(max_length=128, db_comment='Password, hashed in bcrypt.')
    token = models.CharField(max_length=128, db_comment='User token for cookie authentication.')
    joined = models.PositiveBigIntegerField(db_comment="User's join date")
    lastview = models.PositiveBigIntegerField(db_comment='Timestamp of last view')
    featured_submission = models.PositiveBigIntegerField()
    title = models.TextField(db_comment='Display Name')
    about = models.TextField(blank=True, null=True, db_comment="User's description")
    customcolor = models.CharField(max_length=7, blank=True, null=True, db_comment='The color that the user has set for their profile')
    language = models.CharField(max_length=10, db_comment='Language (Defaults to English)')
    avatar = models.IntegerField()
    ip = models.CharField(max_length=48, blank=True, null=True)
    u_flags = models.PositiveIntegerField(db_comment='8 bools to determine certain user properties')
    powerlevel = models.PositiveIntegerField(db_comment='0 - banned. 1 - normal user. 2 - moderator. 3 - administrator')
    group_id = models.IntegerField()
    comfortable_rating = models.CharField(max_length=12)
    blacklisted_tags = models.JSONField(blank=True, null=True)

    class Meta:
        managed = False
        db_table = 'users'


class Videos(models.Model):
    id = models.BigAutoField(primary_key=True, db_comment='Incrementing ID for internal purposes.')
    video_id = models.CharField(max_length=11, db_comment='Random alphanumeric video ID which will be visible.')
    title = models.CharField(max_length=128, db_comment='Video title')
    description = models.TextField(blank=True, null=True, db_comment='Video description')
    author = models.PositiveBigIntegerField(db_comment='User ID of the video author')
    time = models.PositiveBigIntegerField(db_comment='Unix timestamp for the time the video was uploaded')
    most_recent_view = models.PositiveBigIntegerField()
    original_site = models.CharField(max_length=64, blank=True, null=True)
    original_time = models.PositiveBigIntegerField(blank=True, null=True)
    views = models.PositiveBigIntegerField(db_comment='Video views')
    flags = models.PositiveIntegerField(db_comment='8 bools to determine certain video properties')
    category_id = models.IntegerField(blank=True, null=True, db_comment='Category ID for the video')
    videofile = models.TextField(blank=True, null=True, db_comment='Path to the video file(?)')
    videolength = models.PositiveBigIntegerField(blank=True, null=True, db_comment='Length of the video in seconds')
    tags = models.TextField(blank=True, null=True, db_comment='Video tags, serialized in JSON')
    post_type = models.IntegerField(db_comment='The type of the post, 0 is a video, 1 is a legacy video, 2 is art, and 3 is music.')
    rating = models.CharField(max_length=12)

    class Meta:
        managed = False
        db_table = 'videos'


class Views(models.Model):
    video_id = models.TextField()
    user = models.TextField()
    timestamp = models.IntegerField()
    type = models.CharField(max_length=5)

    class Meta:
        managed = False
        db_table = 'views'
