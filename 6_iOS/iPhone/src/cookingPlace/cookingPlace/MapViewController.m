//
//  MapViewController.m
//  cookingPlace
//
//  Created by Daniel Künkel on 21.12.12.
//  Copyright (c) 2012 Daniel Kuenkel. All rights reserved.
//

#import "MapViewController.h"
#import "SMXMLDocument.h"
#import "EventInfo.h"
#import "Events.h"
#import "EventPointAnnotation.h"
#import "EventDetailViewController.h"

@interface MapViewController ()
@end

@implementation MapViewController
@synthesize mapView;


- (id)initWithNibName:(NSString *)nibNameOrNil bundle:(NSBundle *)nibBundleOrNil
{
    self = [super initWithNibName:nibNameOrNil bundle:nibBundleOrNil];
    if (self) {
        // Custom initialization
    }
    return self;
}

- (void)viewDidAppear:(BOOL)animated
{
    [self renderAllEvents];
    
    if (_userLocation != nil) {
        [self updateUserLocation:_userLocation];
    }
}

- (void)viewDidLoad
{
    _isFirstMapInit = YES;
    self.mapView.delegate = self;
    [super viewDidLoad];
}

-(void) viewDidDisappear:(BOOL)animated
{
    [mapView removeAnnotations:mapView.annotations];
}

- (void) renderAllEvents
{
    Events* events = [Events sharedInstance];
    NSMutableArray *allEvents = [events getAllEvents];
    
    for (EventInfo *event in allEvents){
        NSString *address = [NSString stringWithFormat:@"%@ %@ %@ %@", event.street, event.houseNumber, event.zip, event.city];
        CLGeocoder *geocoder = [[CLGeocoder alloc] init];
        [geocoder geocodeAddressString:address completionHandler:^(NSArray *placemarks, NSError *error) {
            if ([placemarks count] > 0) {
                CLPlacemark *placemark = [placemarks objectAtIndex:0];
                CLLocation *location = placemark.location;
                CLLocationCoordinate2D coordinate = location.coordinate;
                
                // Add an annotation
                EventPointAnnotation *point = [[EventPointAnnotation alloc] init];
                point.coordinate = coordinate;
                point.title = event.title;
                point.data = event;
                
                NSDateFormatter *formatter = [[[NSDateFormatter alloc] init] autorelease];
                [formatter setTimeZone:[NSTimeZone timeZoneWithName:@"UTC"]];
                [formatter setDateFormat:@"dd.MM.yyyy HH:mm"];
                NSString *stringFromDate = [formatter stringFromDate:event.date];
                
                point.subtitle = stringFromDate;
                [self.mapView addAnnotation:point];
            }
            [geocoder release];
        }];
    }
}

- (MKAnnotationView *)mapView:(MKMapView *)sender viewForAnnotation:(id <MKAnnotation>)annotation
{    
    if(annotation != mapView.userLocation)
    {
        static NSString *identifier = @"EventPin";
        MKPinAnnotationView *pinView = (MKPinAnnotationView *)[mapView dequeueReusableAnnotationViewWithIdentifier:identifier];
        if ( pinView == nil )
        {
            pinView = [[[MKPinAnnotationView alloc] initWithAnnotation:annotation reuseIdentifier:identifier] autorelease];
        }
        pinView.canShowCallout = YES;
        pinView.animatesDrop = YES;
        pinView.rightCalloutAccessoryView = [UIButton buttonWithType:UIButtonTypeDetailDisclosure];
        
        return pinView;
    }
    else return nil;
}

- (void)mapView:(MKMapView *)mapView annotationView:(MKAnnotationView *)view calloutAccessoryControlTapped:(UIControl *)control
{
    EventPointAnnotation *annotation = (EventPointAnnotation*)[view annotation];
    EventInfo *info = annotation.data;
    
    EventDetailViewController *dvController = [[EventDetailViewController alloc] initWithNibName:@"EventDetailView" bundle:[NSBundle mainBundle]];
    [self.navigationController pushViewController:dvController animated:YES];
    [dvController setEventInfoData:info];
    [info release];
    info = nil;
    [dvController release];
    dvController = nil;
}

- (void)didReceiveMemoryWarning
{
    [super didReceiveMemoryWarning];
    // Dispose of any resources that can be recreated.
}

- (void)mapView:(MKMapView *)mapView didUpdateUserLocation:(MKUserLocation *)userLocation
{
    self.userLocation = userLocation;
    [self updateUserLocation:userLocation];
}

- (void) updateUserLocation:(MKUserLocation *)userLocation
{
    if(_isFirstMapInit == YES)
    {
        _isFirstMapInit = NO;
        MKCoordinateRegion region = MKCoordinateRegionMakeWithDistance(userLocation.coordinate, 800, 800);
        [self.mapView setRegion:region animated:YES];
    }
}

- (void)dealloc {
    [mapView release];
    [super dealloc];
}
- (IBAction)onLocateClick:(id)sender {
    MKCoordinateRegion region = MKCoordinateRegionMakeWithDistance(mapView.userLocation.coordinate, 800, 800);
    [self.mapView setRegion:region animated:YES];
}
@end
