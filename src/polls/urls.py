from django.contrib import admin
from django.urls import include, path
from src.polls import views

urlpatterns = [
    path("", views.index, name="index"),
    path("admin/", admin.site.urls),
]