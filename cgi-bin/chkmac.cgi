#!/usr/bin/perl
use strict;
use CGI;
my %conf;


####### 
$conf{'passwd'} = 'prueba';
$conf{'log_path'} = 'log/';
#######

my $query = new CGI;
my $addr = $ENV{'REMOTE_ADDR'};
my $filename = $query->param('filename');

$filename =~ tr/[a-zA-Z0-9\.\_\-]//cd;
print "Content-type: text/html\n\n";

my $cmd = "./tbk_check_mac.cgi " . $conf{'log_path'} . $filename;

if($query->param('passwd') eq $conf{'passwd'}) {
    exec($cmd);
}
