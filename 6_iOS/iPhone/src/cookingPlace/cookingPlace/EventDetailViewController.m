//
//  EventDetailViewController.m
//  cookingPlace
//
//  Created by Daniel Künkel on 24.12.12.
//  Copyright (c) 2012 Daniel Kuenkel. All rights reserved.
//

#import "EventDetailViewController.h"
#import <QuartzCore/QuartzCore.h>
#import "User.h"
#import "EventDetailContentView.h"
#import "EditEventViewController.h"
#import "Events.h"
#import <EventKit/EventKit.h>

@interface EventDetailViewController ()

@end

@implementation EventDetailViewController



- (id)initWithNibName:(NSString *)nibNameOrNil bundle:(NSBundle *)nibBundleOrNil
{
    self = [super initWithNibName:nibNameOrNil bundle:nibBundleOrNil];
    if (self) {
        // Custom initialization
    }
    return self;
}

- (void)viewDidLoad
{
    eventStoreAccess = [[EKEventStore alloc] init];
    
    [super viewDidLoad];
    [self setTitle:@"Event Details"];
    
    UIBarButtonItem *options = [[UIBarButtonItem alloc]
                                initWithBarButtonSystemItem:UIBarButtonSystemItemAction
                                target:self
                                action:@selector(saveAction:)];
    options.style = UIBarButtonItemStylePlain;
    
    self.navigationItem.rightBarButtonItem = options;
}

- (void)saveAction:(UIBarButtonItem*)sender
{
    UIActionSheet *actionSheet = [[UIActionSheet alloc] initWithTitle:@"Choose an event option"
                                                             delegate:self
                                                    cancelButtonTitle:@"Cancel"
                                               destructiveButtonTitle:nil
                                                    otherButtonTitles: @"Add to calendar", @"Add reminder", nil];
    
    actionSheet.actionSheetStyle = UIActionSheetStyleBlackOpaque;
    [actionSheet showInView:self.parentViewController.parentViewController.view];
    [actionSheet release];
}

-(void)actionSheet:(UIActionSheet *)actionSheet clickedButtonAtIndex:(NSInteger)buttonIndex {
    switch (buttonIndex) {
        case 0:
            [self addToCalendar];
            break;
        case 1:
            break;
    }
}

- (void) addToCalendar
{
    if([eventStoreAccess respondsToSelector:@selector(requestAccessToEntityType:completion:)]) {
        // iOS 6 and later
        [eventStoreAccess requestAccessToEntityType:EKEntityTypeEvent completion:^(BOOL granted, NSError *error) {
            
            // perform the main thread here to avoid any delay. normally seems to be 10 to 15 sec delay.
            [self performSelectorOnMainThread: @selector(presentEventEditViewControllerWithEventStore:) withObject:eventStoreAccess waitUntilDone:NO];
            
            if (granted){
                
            }else
            {
                NSLog(@"!granted");
                UIAlertView *alert = [[UIAlertView alloc] initWithTitle:@"You've not allow to access your calendar" message:@"Pleace check your preferences" delegate:self cancelButtonTitle:@"Close" otherButtonTitles:NULL, nil];
                [alert show];
            }
        }];
    }
}


- (void)presentEventEditViewControllerWithEventStore:(EKEventStore*)eventStore
{
    eventStoreAccess = eventStore;
    
    NSDateFormatter *formatter = [[[NSDateFormatter alloc] init] autorelease];
    [formatter setTimeZone:[NSTimeZone timeZoneWithName:@"UTC"]];
    
    NSDate *startDate = [eventInfoData.date dateByAddingTimeInterval:-3600];
    NSDate *endDate = [eventInfoData.date dateByAddingTimeInterval:14400];
    NSPredicate *predicate = [eventStore predicateForEventsWithStartDate:startDate
                                                                  endDate:endDate calendars:nil];
    
    NSArray *matchingEvents = [eventStore eventsMatchingPredicate:predicate];
    BOOL eventExists = NO;
    
    // There are already event(s) which match this date/time start and end.
    // Check if this event is the PV
    for (EKEvent *anEvent in matchingEvents) {
        if([eventInfoData.title isEqualToString: anEvent.title])
        {
            eventExists = YES;
        }
    }    
    
    if(eventExists == YES)
    {
        UIAlertView *alert = [[UIAlertView alloc] initWithTitle:@"Event exists" message:@"This event already exists in your calendar" delegate:self cancelButtonTitle:@"Okay" otherButtonTitles:NULL, nil];
        [alert show];
    }
    else
    {
        EKEvent *newEvent = [EKEvent eventWithEventStore:eventStore];
        newEvent.title = eventInfoData.title;
        newEvent.location = [NSString stringWithFormat:@"%@ %@, %@ %@", eventInfoData.street, eventInfoData.houseNumber, eventInfoData.zip, eventInfoData.city];
        newEvent.startDate = startDate;
        newEvent.endDate = endDate;
        newEvent.notes = eventInfoData.abstract;
        [newEvent setCalendar:[eventStore defaultCalendarForNewEvents]];
        
        NSError *err;
        [eventStore saveEvent:newEvent span:EKSpanThisEvent error:&err];
        
        if(err == noErr)
        {
            UIAlertView *alert = [[UIAlertView alloc] initWithTitle:@"" message:@"Insert entry into your calendar" delegate:self cancelButtonTitle:@"Okay" otherButtonTitles:NULL, nil];
            [alert show];
        }
        else
        {
            NSLog(@"error: %@", err);
            UIAlertView *alert = [[UIAlertView alloc] initWithTitle:@"There was an error" message:@"Try to restart the app" delegate:self cancelButtonTitle:@"Close" otherButtonTitles:NULL, nil];
            [alert show];
        }
    }
}

- (void)viewDidAppear:(BOOL)animated
{
    Events *events = [Events sharedInstance];
    NSArray *nibContents = [[NSBundle mainBundle] loadNibNamed:@"EventDetailContentView" owner:self options:nil];
    EventDetailContentView *content = [nibContents objectAtIndex:0];
    eventInfoData = [events getEventById:eventInfoData.eventId];
    content.eventInfoData = eventInfoData;
    content.controller = self.navigationController;
    [content setDelegate:self];
    [content sizeToFit];
    
    [_scrollView setContentSize:CGSizeMake(content.frame.size.width, content.frame.size.height)];
    [_scrollView addSubview:content];
}


-(void)showEditEventForm
{
    [_scrollView scrollsToTop];
    EditEventViewController *viewController = [[[EditEventViewController alloc] initWithNibName:@"EditEventViewController" bundle:nil] autorelease];
    viewController.eventInfoData = eventInfoData;
    [self.navigationController pushViewController:viewController animated:YES];
}

-(void)setEventInfoData:(EventInfo *)infoData
{
    eventInfoData = infoData;
}

-(EventInfo *)eventInfoData
{
    return eventInfoData;
}

- (void)didReceiveMemoryWarning
{
    [super didReceiveMemoryWarning];
    // Dispose of any resources that can be recreated.
}

- (void)dealloc {
    [_scrollView release];
    [super dealloc];
}
@end
