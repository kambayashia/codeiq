package Log;
use strict;
use warnings;
use DateTime;

sub new {
    my ($class, %args) = @_;
    return bless \%args, $class;
}

sub protocol {
    my ($self) = @_;
    my $result = "";
    my %elements = $self->parse_request();
    $result = $elements{protocol};
    return $result;
}

sub method {
    my ($self) = @_;
    my $result = "";
    my %elements = $self->parse_request();
    $result = $elements{method};
    return $result;
}

sub path {
    my ($self) = @_;
    my $result = "";
    my %elements = $self->parse_request();
    $result = $elements{path};
    return $result;
}

sub uri {
    my ($self) = @_;
    my $result = "";
    my %elements = $self->parse_request();
    my $scheme = 'http://';
    $result = $scheme . $self->{host} . $elements{path};
    return $result;
}

sub time {
    my ($self) = @_;
    my $result = "";
    my $dt = DateTime->from_epoch( time_zone => 'GMT', epoch => $self->{epoch} );
    $result = $dt->strftime('%Y-%m-%dT%H:%M:%S');
    return $result;
}

sub parse_request{
    my ($self) = @_;
    my @parts = split( / /, $self->{req} );
    my %elements = (
        method => $parts[0],
        path => $parts[1],
        protocol => $parts[2]
    );
    return %elements;
}

1;
