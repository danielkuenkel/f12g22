//
//  EditEventViewController.m
//  cookingPlace
//
//  Created by Daniel Künkel on 28.12.12.
//  Copyright (c) 2012 Daniel Kuenkel. All rights reserved.
//

#import "EditEventViewController.h"
#import "EditEventView.h"
#import "User.h"
#import "SMXMLDocument.h"
#import "Events.h"

@interface EditEventViewController ()

@end

@implementation EditEventViewController
@synthesize eventInfoData;
@synthesize content;

- (id)initWithNibName:(NSString *)nibNameOrNil bundle:(NSBundle *)nibBundleOrNil
{
    self = [super initWithNibName:nibNameOrNil bundle:nibBundleOrNil];
    if (self) {
        // Custom initialization
    }
    return self;
}

-(void)viewDidLoad
{
    [super viewDidLoad];
    [self setTitle:@"Edit Event"];
}

- (void)viewDidAppear:(BOOL)animated
{    
    NSArray *nibContents = [[NSBundle mainBundle] loadNibNamed:@"EditEventView" owner:self options:nil];
    content = [nibContents objectAtIndex:0];
    content.eventInfoData = eventInfoData;
    [content setDelegate:self];
    content.controller = self.navigationController;
    [content sizeToFit];
    
    _scrollView.autoresizingMask = UIViewAutoresizingFlexibleHeight | UIViewAutoresizingFlexibleWidth;
    [_scrollView setContentSize:CGSizeMake(content.frame.size.width, content.frame.size.height)];
    [_scrollView addSubview:content];
    
    [self registerForKeyboardNotifications];
    [self resetForm];
}


- (void)registerForKeyboardNotifications
{
    [[NSNotificationCenter defaultCenter] addObserver:self
                                             selector:@selector(keyboardWasShown:)
                                                 name:UIKeyboardDidShowNotification object:nil];
    
    [[NSNotificationCenter defaultCenter] addObserver:self
                                             selector:@selector(keyboardWillBeHidden:)
                                                 name:UIKeyboardWillHideNotification object:nil];
    
}

// Called when the UIKeyboardDidShowNotification is sent.
- (void)keyboardWasShown:(NSNotification*)aNotification
{
    content.resetInsets = NO;
    _scrollView.scrollEnabled = NO;
    
    NSDictionary* info = [aNotification userInfo];
    CGSize kbSize = [[info objectForKey:UIKeyboardFrameBeginUserInfoKey] CGRectValue].size;
    
    UIEdgeInsets contentInsets = UIEdgeInsetsMake(0.0, 0.0, kbSize.height, 0.0);
    _scrollView.contentInset = contentInsets;
    _scrollView.scrollIndicatorInsets = contentInsets;
    

    // If active text field is hidden by keyboard, scroll it so it's visible
    // Your application might not need or want this behavior.
    CGRect aRect = content.frame;
    aRect.size.height -= kbSize.height + 202;
    if (!CGRectContainsPoint(aRect, content.activeField.frame.origin))
    {
        CGPoint scrollPoint = CGPointMake(0.0, content.activeField.frame.origin.y - kbSize.height + 60);
        [_scrollView setContentOffset:scrollPoint animated:YES];
    }
}

// Called when the UIKeyboardWillHideNotification is sent
- (void)keyboardWillBeHidden:(NSNotification*)aNotification
{
    content.resetInsets = YES;
    [_scrollView setContentOffset:CGPointMake(0.0, content.frame.size.height - _scrollView.frame.size.height) animated:YES];
}

-(void)scrollViewDidScroll:(UIScrollView *)sender
{
    [self performSelector:@selector(scrollViewDidEndScrollingAnimation:) withObject:nil afterDelay:0.4];
}

-(void)scrollViewDidEndScrollingAnimation:(UIScrollView *)scrollView
{
    [NSObject cancelPreviousPerformRequestsWithTarget:self];
    
    if(content.resetInsets)
    {
        _scrollView.scrollEnabled = YES;
        UIEdgeInsets contentInsets = UIEdgeInsetsMake(0.0, 0.0, 0.0, 0.0);
        _scrollView.contentInset = contentInsets;
        _scrollView.scrollIndicatorInsets = contentInsets;
    }
}

-(void)submitEventData
{
    
    if(![content personsValid])
    {
        NSString *message = [NSString stringWithFormat:@"You have to specify between %i to %i people", 2, 99];
        UIAlertView *alert = [[UIAlertView alloc] initWithTitle:@"Invalid persons"  message:message delegate:self cancelButtonTitle:@"Okay"  otherButtonTitles:NULL, nil];
        [alert show];
    }
    else if(![content maxPersonsValid:eventInfoData.participants.count])
    {
        NSString *message = [NSString stringWithFormat:@"There have been %i people registered for this event. So you have to specify at least for %i people (maximum of 99)", eventInfoData.participants.count - 1, eventInfoData.participants.count];
        UIAlertView *alert = [[UIAlertView alloc] initWithTitle:@"Check number of persons"  message:message delegate:self cancelButtonTitle:@"Close"  otherButtonTitles:NULL, nil];
        [alert show];
    }
    else if(![content costsValid])
    {
        NSString *message = [NSString stringWithFormat:@"Costs must e.g. defined to be 98.76"];
        UIAlertView *alert = [[UIAlertView alloc] initWithTitle:@"Invalid costs"  message:message delegate:self cancelButtonTitle:@"Close"  otherButtonTitles:NULL, nil];
        [alert show];
    }
    else if([content textfieldsValid])
    {
        [self submitData];
    }
    else
    {
        UIAlertView *alert = [[UIAlertView alloc] initWithTitle:@"Update Event failed" message:@"Please fill in all text fields." delegate:self cancelButtonTitle:@"Okay" otherButtonTitles:NULL, nil];
        [alert show];
    }
}


- (void) submitData
{
    NSDateFormatter *dateFormatter = [[[NSDateFormatter alloc]init]autorelease];
    dateFormatter.dateFormat = @"dd/MM/yyyy";
    NSString *date = [dateFormatter stringFromDate: content.datePicker.date];
    
    dateFormatter.dateFormat = @"HH";
    NSString *hour = [dateFormatter stringFromDate: content.datePicker.date];
    
    dateFormatter.dateFormat = @"mm";
    NSString *minute = [dateFormatter stringFromDate: content.datePicker.date];
    
    User* user = [User sharedInstance];
    
    NSString *post = [NSString stringWithFormat:@"date=%@&hour=%@&minute=%@", date, hour, minute];
    
    post = [post stringByAppendingFormat:@"&updateEventId=%d", eventInfoData.eventId];
    post = [post stringByAppendingFormat:@"&userId=%d", user.userId];
    post = [post stringByAppendingFormat:@"&eventTitle=%@", content.titleInput.text];
    post = [post stringByAppendingFormat:@"&maxPersons=%@", content.personsInput.text];
    post = [post stringByAppendingFormat:@"&cost=%@", content.costInput.text];
    post = [post stringByAppendingFormat:@"&street=%@", content.streetInput.text];
    post = [post stringByAppendingFormat:@"&housenumber=%@", content.houseNumberInput.text];
    post = [post stringByAppendingFormat:@"&zipcode=%@", content.zipInput.text];
    post = [post stringByAppendingFormat:@"&city=%@", content.cityInput.text];
    post = [post stringByAppendingFormat:@"&description=%@", content.absractInput.text];
    
    NSString *hostStr = @"http://sfsuswe.com/~f12g22/web/php/UploadEvent.php?";
    hostStr = [hostStr stringByAppendingString:post];
    NSString *safestring=[hostStr stringByAddingPercentEscapesUsingEncoding:NSUTF8StringEncoding];
    NSData *dataURL =  [NSData dataWithContentsOfURL: [ NSURL URLWithString: safestring ]];
    
    
    // create a new SMXMLDocument with the contents of the php response as XML
    NSError *error;
	SMXMLDocument *document = [SMXMLDocument documentWithData:dataURL error:&error];
    
    // check for errors
    if (error) {
        NSLog(@"Error while parsing the document: %@", error);
        UIAlertView *alert = [[UIAlertView alloc] initWithTitle:@"Update Event failed" message:@"There was an error. Please try again later" delegate:self cancelButtonTitle:@"Close" otherButtonTitles:NULL, nil];
        [alert show];
    }
    
    // Get the videos node
    SMXMLElement *event = document.root;
    int created = [[event valueWithPath:@"created"] intValue];
    if(created == 1)
    {
        Events *events = [Events sharedInstance];
        [events getAllEvents];
        eventInfoData = [events getEventById:eventInfoData.eventId];
        
        UIAlertView *alert = [[UIAlertView alloc] initWithTitle:@"Updated Event successful" message:@"Your event has been successfully updated" delegate:self cancelButtonTitle:@"Close"  otherButtonTitles:NULL, nil];
        [alert addButtonWithTitle:@"Show Event"];
        [alert addButtonWithTitle:@"Show Event List"];
        [alert show];
    }
    else
    {
        UIAlertView *alert = [[UIAlertView alloc] initWithTitle:@"Update Event failed" message:@"There was an error. Please try again later" delegate:self cancelButtonTitle:@"Close" otherButtonTitles:NULL, nil];
        [alert show];
    }
}

- (void) resetForm
{
    //set the event data into the form fields
    content.titleInput.text = eventInfoData.title;
    content.personsInput.text = [NSString stringWithFormat:@"%d", eventInfoData.maxParticipants];
    content.costInput.text = [NSString stringWithFormat:@"%.2f", eventInfoData.cost];
    content.streetInput.text = eventInfoData.street;
    content.houseNumberInput.text = eventInfoData.houseNumber;
    content.zipInput.text = eventInfoData.zip;
    content.cityInput.text = eventInfoData.city;
    content.absractInput.text = eventInfoData.abstract;
    
    NSDate *date = eventInfoData.date;
    
    NSTimeZone *tz = [NSTimeZone defaultTimeZone];
    NSInteger seconds = -[tz secondsFromGMTForDate: date];
    date = [NSDate dateWithTimeInterval: seconds sinceDate: date];
    
    content.datePicker.date = date;
    content.datePicker.minimumDate = [NSDate date];
}

- (void) alertView: (UIAlertView *) alertView clickedButtonAtIndex:(NSInteger) buttonIndex
{
    switch (buttonIndex) {
        case 2:
            [self.navigationController popToRootViewControllerAnimated:YES];
            break;
        case 1:
            [self.navigationController popViewControllerAnimated:YES];
            break;
    }
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
