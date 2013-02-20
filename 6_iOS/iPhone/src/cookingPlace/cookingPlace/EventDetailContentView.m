//
//  EventDetailContentView.m
//  cookingPlace
//
//  Created by Daniel Künkel on 28.12.12.
//  Copyright (c) 2012 Daniel Kuenkel. All rights reserved.
//

#import "EventDetailContentView.h"
#import "User.h"
#import <QuartzCore/QuartzCore.h>
#import "SMXMLDocument.h"
#import "Events.h"

@implementation EventDetailContentView

@synthesize eventInfoData;
@synthesize controller;
@synthesize delegate;

- (id)initWithFrame:(CGRect)frame
{
    self = [super initWithFrame:frame];
    if (self) {
        // Initialization code
    }
    self.autoresizingMask = UIViewAutoresizingFlexibleHeight | UIViewAutoresizingFlexibleWidth;
    return self;
}

-(void)sizeToFit
{
    [[mapView layer] setMasksToBounds:NO];
    [[mapView layer] setShadowOffset:CGSizeMake(0, 0)];
    [[mapView layer] setShadowColor:[[UIColor blackColor] CGColor]];
    [[mapView layer] setShadowRadius:10.0];
    [[mapView layer] setShadowOpacity:1];
    mapView.layer.shadowPath = [UIBezierPath bezierPathWithRect:mapView.bounds].CGPath;
    
    
    // render ui objects
    eventTitle.text = eventInfoData.title;
    eventTitle.lineBreakMode = NSLineBreakByWordWrapping;
    eventTitle.numberOfLines = 0;
    [eventTitle sizeToFit];
    
    createdBy.text = [NSString stringWithFormat:@"created by %@", eventInfoData.ownerName];
    [createdBy sizeToFit];
    
    abstract.text = eventInfoData.abstract;
    abstract.lineBreakMode = NSLineBreakByWordWrapping;
    abstract.numberOfLines = 0;
    [abstract sizeToFit];
    
    participants.text = eventInfoData.getAllParticipants;
    participants.lineBreakMode = NSLineBreakByWordWrapping;
    participants.numberOfLines = 0;
    [participants sizeToFit];
    
    cost.text = [NSString stringWithFormat:@"%.2f EUR total / %.2f EUR rata", eventInfoData.cost, eventInfoData.cost / eventInfoData.participants.count];
    cost.lineBreakMode = NSLineBreakByWordWrapping;
    cost.numberOfLines = 0;
    [cost sizeToFit];
    
    places.text = [NSString stringWithFormat:@"%d total / %u left place(s)", eventInfoData.maxParticipants, eventInfoData.maxParticipants - eventInfoData.participants.count];
    places.lineBreakMode = NSLineBreakByWordWrapping;
    places.numberOfLines = 0;
    [places sizeToFit];
    
    adress.text = [NSString stringWithFormat:@"%@ %@, %@ %@", eventInfoData.street, eventInfoData.houseNumber, eventInfoData.zip, eventInfoData.city];
    adress.lineBreakMode = NSLineBreakByWordWrapping;
    adress.numberOfLines = 0;
    [adress sizeToFit];
    
    NSDateFormatter *formatter = [[[NSDateFormatter alloc] init] autorelease];
    [formatter setTimeZone:[NSTimeZone timeZoneWithName:@"UTC"]];
    [formatter setDateFormat:@"dd.MM.yyyy 'at' HH:mm"];
    date.text = [NSString stringWithFormat:@"%@", [formatter stringFromDate:eventInfoData.date]];
    date.lineBreakMode = NSLineBreakByWordWrapping;
    date.numberOfLines = 0;
    [date sizeToFit];
    
    
    // set pattern background
    self.backgroundColor = [[UIColor alloc] initWithPatternImage:[UIImage imageNamed:@"black_linen_v2.png"]];
    
    
    // annotate event address
    NSString *address = [NSString stringWithFormat:@"%@ %@ %@ %@", eventInfoData.street, eventInfoData.houseNumber, eventInfoData.zip, eventInfoData.city];
    CLGeocoder *geocoder = [[CLGeocoder alloc] init];
    [geocoder geocodeAddressString:address completionHandler:^(NSArray *placemarks, NSError *error) {
        if ([placemarks count] > 0) {
            CLPlacemark *placemark = [placemarks objectAtIndex:0];
            CLLocation *location = placemark.location;
            CLLocationCoordinate2D coordinate = location.coordinate;
            
            // Add an annotation
            MKPointAnnotation *point = [[MKPointAnnotation alloc] init];
            point.coordinate = coordinate;
            
            [mapView addAnnotation:point];
            
            MKCoordinateRegion region = MKCoordinateRegionMakeWithDistance(coordinate, 600, 600);
            [mapView setRegion:region animated:NO];
        }
        [geocoder release];
    }];
    
    // calculate view size height in case of subview size heights
    int lastY = 0;
    for (UIView *subView in self.subviews) {
        CGRect newFrame = CGRectMake(subView.frame.origin.x, subView.frame.origin.y + subView.bounds.size.height, subView.frame.size.width, subView.frame.size.height);
        subView.frame = newFrame;
        if(![subView isKindOfClass:[UIImageView class]] && ![subView isKindOfClass:[UIButton class]])
        {
            lastY += subView.frame.size.height;
        }
    }
    
    
    //manual set of a custom button background image
    UIImage *redButtonImage = [[UIImage imageNamed:@"button-red.png"]
                               resizableImageWithCapInsets:UIEdgeInsetsMake(5, 5, 5, 5)];
    UIImage *redButtonImageHighlight = [[UIImage imageNamed:@"button-red-highlight.png"]
                                        resizableImageWithCapInsets:UIEdgeInsetsMake(5, 5, 5, 5)];
    
    [cancelButton setBackgroundImage:redButtonImage forState:UIControlStateNormal];
    [cancelButton setBackgroundImage:redButtonImageHighlight forState:UIControlStateHighlighted];
    
    UIImage *greenButtonImage = [[UIImage imageNamed:@"button-green.png"]
                                 resizableImageWithCapInsets:UIEdgeInsetsMake(5, 5, 5, 5)];
    UIImage *greenButtonImageHighlight = [[UIImage imageNamed:@"button-green-highlight.png"]
                                          resizableImageWithCapInsets:UIEdgeInsetsMake(5, 5, 5, 5)];
    
    [editButton setBackgroundImage:greenButtonImage forState:UIControlStateNormal];
    [editButton setBackgroundImage:greenButtonImageHighlight forState:UIControlStateHighlighted];
    
    [joinButton setBackgroundImage:greenButtonImage forState:UIControlStateNormal];
    [joinButton setBackgroundImage:greenButtonImageHighlight forState:UIControlStateHighlighted];
    
    
    // show and hide needed buttons
    editButton.hidden = YES;
    cancelButton.hidden = YES;
    joinButton.hidden = YES;
    
    User *user = [User sharedInstance];
    if(eventInfoData.isOwner == 1)
    {
        editButton.hidden = NO;
        cancelButton.hidden = NO;
        
        lastY += editButton.frame.size.height + cancelButton.frame.size.height;
    }
    else if ([eventInfoData hasJoinedEvent:user.logonName])
    {
        joinButton.hidden = NO;
        [joinButton setTitle:@"LEAVE EVENT" forState:UIControlStateNormal];
        [joinButton setTitle:@"LEAVE EVENT" forState:UIControlStateHighlighted];
        
        lastY += joinButton.frame.size.height;
    }
    else if (eventInfoData.maxParticipants - eventInfoData.participants.count > 0)
    {
        [joinButton setTitle:@"JOIN EVENT" forState:UIControlStateNormal];
        [joinButton setTitle:@"JOIN EVENT" forState:UIControlStateHighlighted];
        joinButton.hidden = NO;
        
        lastY += joinButton.frame.size.height;
    }
    self.frame = CGRectMake(0, 0, self.frame.size.width, lastY + 100);
}

- (void)dealloc
{
    [mapView release];
    [eventTitle release];
    [abstract release];
    [createdBy release];
    [participants release];
    [cost release];
    [places release];
    [adress release];
    [date release];
    
    [cancelButton release];
    [editButton release];
    [joinButton release];
    
    [super dealloc];
}

- (IBAction)onEditTap:(UIButton *)sender {
    [self showEditEventFormDelegate];
}

- (IBAction)onCancelTap:(UIButton *)sender {
    UIActionSheet *actionSheet = [[UIActionSheet alloc] initWithTitle:@"Are you sure you want to cancel this event?"
                                                             delegate:self
                                                    cancelButtonTitle:@"Cancel"
                                               destructiveButtonTitle:@"OK"
                                                    otherButtonTitles: nil];
    
    actionSheet.actionSheetStyle = UIActionSheetStyleBlackOpaque;
    [actionSheet showInView:self];
    [actionSheet release];
}

-(void)actionSheet:(UIActionSheet *)actionSheet clickedButtonAtIndex:(NSInteger)buttonIndex {
    switch (buttonIndex) {
        case 0:
            [self cancelEvent];
            break;
    }
}

- (void) cancelEvent
{
    NSString *post = [NSString stringWithFormat:@"eventId=%d", eventInfoData.eventId];
    
    NSString *hostStr = @"http://sfsuswe.com/~f12g22/web/php/DeleteEvent.php?";
    hostStr = [hostStr stringByAppendingString:post];
    NSString *safestring=[hostStr stringByAddingPercentEscapesUsingEncoding:NSUTF8StringEncoding];
    NSData *dataURL =  [NSData dataWithContentsOfURL: [ NSURL URLWithString: safestring ]];
    
    // create a new SMXMLDocument with the contents of the php response as XML
    NSError *error;
	SMXMLDocument *document = [SMXMLDocument documentWithData:dataURL error:&error];
    
    // check for errors
    if (error) {
        NSLog(@"Error while parsing the document: %@", error);
        UIAlertView *alert = [[UIAlertView alloc] initWithTitle:@"Cancel event failed" message:@"There was an error. Please try again later" delegate:self cancelButtonTitle:@"Close" otherButtonTitles:NULL, nil];
        [alert show];
    }
    
    // Get the videos node
    SMXMLElement *event = document.root;
    NSString *status = [event valueWithPath:@"status"];
    if([status isEqualToString:@"okay"])
    {
        UIAlertView *alert = [[UIAlertView alloc] initWithTitle:@"Cancel event success" message:@"Your event has been successfully canceled" delegate:self cancelButtonTitle:@"Okay"  otherButtonTitles:NULL, nil];
        [alert show];
    }
    else
    {
        UIAlertView *alert = [[UIAlertView alloc] initWithTitle:@"Cancel event failed" message:@"There was an error. Please try again later" delegate:self cancelButtonTitle:@"Close" otherButtonTitles:NULL, nil];
        [alert show];
    }
}

- (IBAction)onJoinTap:(UIButton *)sender {
    
    User *user = [User sharedInstance];
    if ([eventInfoData hasJoinedEvent:user.logonName])
    {
        [self leaveEvent];
    }
    else
    {
        [self joinEvent];
    }
}

-(void) joinEvent
{
    User *user = [User sharedInstance];
    NSString *post = [NSString stringWithFormat:@"eventId=%i&userId=%i", eventInfoData.eventId, user.userId];
    
    NSString *hostStr = @"http://sfsuswe.com/~f12g22/web/php/JoinEvent.php?";
    hostStr = [hostStr stringByAppendingString:post];
    NSString *safestring=[hostStr stringByAddingPercentEscapesUsingEncoding:NSUTF8StringEncoding];
    NSData *dataURL =  [NSData dataWithContentsOfURL: [ NSURL URLWithString: safestring ]];
    
    // create a new SMXMLDocument with the contents of the php response as XML
    NSError *error;
	SMXMLDocument *document = [SMXMLDocument documentWithData:dataURL error:&error];
    
    // check for errors
    if (error) {
        NSLog(@"Error while parsing the document: %@", error);
        UIAlertView *alert = [[UIAlertView alloc] initWithTitle:@"Join event failed" message:@"There was an error. Please try again later" delegate:self cancelButtonTitle:@"Close" otherButtonTitles:NULL, nil];
        alert.tag = 3;
        [alert show];
    }
    
    // Get the joined node
    SMXMLElement *event = document.root;
    int joined = [[event valueWithPath:@"joined"] intValue];
    if(joined == 1)
    {
        Events *events = [Events sharedInstance];
        [events getAllEvents];
        
        int eventId = [[event valueWithPath:@"eventId"] intValue];
        eventInfoData = [events getEventById:eventId];
        [self sizeToFit];
        
        UIAlertView *alert = [[UIAlertView alloc] initWithTitle:@"Joined event successful" message:@"You have joined this event. Please do not forget to appear at the specified place at the specified date! On current cost and participants you will informed via E-Mail." delegate:self cancelButtonTitle:@"Okay"  otherButtonTitles:NULL, nil];
        alert.tag = 3;
        [alert show];
    }
    else
    {
        UIAlertView *alert = [[UIAlertView alloc] initWithTitle:@"Join event failed" message:@"There was an error. Please try again later" delegate:self cancelButtonTitle:@"Close" otherButtonTitles:NULL, nil];
        alert.tag = 3;
        [alert show];
    }
}


-(void) leaveEvent
{
    User *user = [User sharedInstance];
    NSString *post = [NSString stringWithFormat:@"eventId=%i&userId=%i", eventInfoData.eventId, user.userId];
    
    NSString *hostStr = @"http://sfsuswe.com/~f12g22/web/php/LeaveEvent.php?";
    hostStr = [hostStr stringByAppendingString:post];
    NSString *safestring=[hostStr stringByAddingPercentEscapesUsingEncoding:NSUTF8StringEncoding];
    NSData *dataURL =  [NSData dataWithContentsOfURL: [ NSURL URLWithString: safestring ]];
    
    // create a new SMXMLDocument with the contents of the php response as XML
    NSError *error;
	SMXMLDocument *document = [SMXMLDocument documentWithData:dataURL error:&error];
    
    // check for errors
    if (error) {
        NSLog(@"Error while parsing the document: %@", error);
        UIAlertView *alert = [[UIAlertView alloc] initWithTitle:@"Leave event failed" message:@"There was an error. Please try again later" delegate:self cancelButtonTitle:@"Close" otherButtonTitles:NULL, nil];
        alert.tag = 3;
        [alert show];
    }
    
    // Get the joined node
    SMXMLElement *event = document.root;
    int joined = [[event valueWithPath:@"joined"] intValue];
    if(joined == 0)
    {
        Events *events = [Events sharedInstance];
        [events getAllEvents];
        
        int eventId = [[event valueWithPath:@"eventId"] intValue];
        eventInfoData = [events getEventById:eventId];
        [self sizeToFit];
        
        UIAlertView *alert = [[UIAlertView alloc] initWithTitle:@"Leaved event successful" message:@"You have leaved this event." delegate:self cancelButtonTitle:@"Okay"  otherButtonTitles:NULL, nil];
        alert.tag = 3;
        [alert show];
    }
    else
    {
        UIAlertView *alert = [[UIAlertView alloc] initWithTitle:@"Leave event failed" message:@"There was an error. Please try again later" delegate:self cancelButtonTitle:@"Close" otherButtonTitles:NULL, nil];
        alert.tag = 3;
        [alert show];
    }
}

-(void)showEditEventFormDelegate
{
    [delegate showEditEventForm];
}


- (void) alertView: (UIAlertView *) alertView clickedButtonAtIndex:(NSInteger) buttonIndex
{
    switch (buttonIndex) {
        case 0:
            if(alertView.tag == 3)
            {
                [self sizeToFit];
            }
            else{
                [controller popToRootViewControllerAnimated:YES];
            }
            break;
    }
}

@end
