#!/usr/bin/perl -w
use strict;
open MIMETYPES, "/etc/mime.types" or exit;
print "mimetype.assign = (\n";
my %extensions;
while(<MIMETYPES>) {
  chomp;
  s/\#.*//;
  next if /^\w*$/;
  if(/^([a-z0-9\/+-.]+)\s+((?:[a-z0-9.+-]+[ ]?)+)$/) {
    my $pup = $1;
    foreach(split / /, $2) {
      # mime.types can have same extension for different
      # mime types
      next if $extensions{$_};
      next if not defined $pup;
      next if $pup eq '';
      $extensions{$_} = 1;
      if ($pup =~ /^text\//) {
        print "\".$_\" => \"$pup; charset=utf-8\",\n";
      } else {
        print "\".$_\" => \"$pup\",\n";
      }
    }
  }
}
print ")\n";
