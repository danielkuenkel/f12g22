//
//  CreateEventViewController.m
//  cookingPlace
//
//  Created by Daniel Künkel on 19.12.12.
//  Copyright (c) 2012 Daniel Kuenkel. All rights reserved.
//

#import "CreateEventViewController.h"
#import "User.h"
#import "SMXMLDocument.h"
#import "EventDetailViewController.h"
#import "Events.h"

@interface CreateEventViewController ()

@end

@implementation CreateEventViewController

@synthesize content;

- (id)initWithNibName:(NSString *)nibNameOrNil bundle:(NSBundle *)nibBundleOrNil
{
    self = [super initWithNibName:nibNameOrNil bundle:nibBundleOrNil];
    if (self) {
    }
    return self;
}

-(void)viewDidAppear:(BOOL)animated
{
    [super viewDidAppear:animated];
    
    NSArray *nibContents = [[NSBundle mainBundle] loadNibNamed:@"EditEventView" owner:self options:nil];
    content = [nibContents objectAtIndex:0];
    [content setDelegate:self];
    content.controller = self.navigationController;
    [content sizeToFit];
    
    _scrollView.autoresizingMask = UIViewAutoresizingFlexibleHeight | UIViewAutoresizingFlexibleWidth;
    [_scrollView setContentSize:CGSizeMake(content.frame.size.width, content.frame.size.height)];
    [_scrollView addSubview:content];
    
    [self registerForKeyboardNotifications];
    [self resetForm];
}

-(void)viewDidDisappear:(BOOL)animated
{
}

- (void)didReceiveMemoryWarning
{
    [super didReceiveMemoryWarning];
    // Dispose of any resources that can be recreated.
}

- (void)dealloc {
    [_scrollView release];
    [content release];
    [super dealloc];
}

-(void)submitEventData
{
    if(![content personsValid])
    {
        NSString *message = [NSString stringWithFormat:@"You have to specify between %i to %i people", 2, 99];
        UIAlertView *alert = [[UIAlertView alloc] initWithTitle:@"Invalid persons"  message:message delegate:self cancelButtonTitle:@"Okay"  otherButtonTitles:NULL, nil];
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
        UIAlertView *alert = [[UIAlertView alloc] initWithTitle:@"Create event failed" message:@"Please fill in all text fields." delegate:self cancelButtonTitle:@"Okay" otherButtonTitles:NULL, nil];
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
    
    post = [post stringByAppendingFormat:@"&updateEventId="];
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
        UIAlertView *alert = [[UIAlertView alloc] initWithTitle:@"Event submit failed" message:@"There was an error. Please try again later" delegate:self cancelButtonTitle:@"Close" otherButtonTitles:NULL, nil];
        [alert show];
    }
    
    // Get the videos node
    SMXMLElement *event = document.root;
    int created = [[event valueWithPath:@"created"] intValue];
    createdEventId = [[event valueWithPath:@"eventId"] integerValue];
    if(created == 1)
    {
        [self resetForm];
        
        UIAlertView *alert = [[UIAlertView alloc] initWithTitle:@"Event submit success" message:@"Your event has been successfully created" delegate:self cancelButtonTitle:@"Close"  otherButtonTitles:NULL, nil];
        [alert addButtonWithTitle:@"Show Event Map"];
        [alert addButtonWithTitle:@"Show Event List"];
        [alert addButtonWithTitle:@"Show Event" ];
        [alert show];
    }
    else
    {
        UIAlertView *alert = [[UIAlertView alloc] initWithTitle:@"Event submit failed" message:@"There was an error. Please try again later" delegate:self cancelButtonTitle:@"Close" otherButtonTitles:NULL, nil];
        [alert show];
    }
}

- (void) resetForm
{
    User *user = [User sharedInstance];
    
    //set the event data into the form fields
    content.titleInput.text = @"";
    content.personsInput.text = @"";
    content.costInput.text = @"";
    content.streetInput.text = user.street;
    content.houseNumberInput.text = [NSString stringWithFormat:@"%d", user.houseNumber];
    content.zipInput.text = [NSString stringWithFormat:@"%d", user.zip];
    content.cityInput.text = user.city;
    content.absractInput.text = @"";
    
    content.datePicker.date = [NSDate date];
    content.datePicker.minimumDate = [NSDate date];
}


- (void) alertView: (UIAlertView *) alertView clickedButtonAtIndex:(NSInteger) buttonIndex
{
    switch (buttonIndex) {
        case 1:
            self.tabBarController.selectedViewController = [self.tabBarController.viewControllers objectAtIndex:0];
            break;
        case 2:
            self.tabBarController.selectedViewController = [self.tabBarController.viewControllers objectAtIndex:1];
            break;
        case 3:
            if(createdEventId != 0)
            {
                Events *events = [Events sharedInstance];
                [events getAllEvents];
                EventInfo *info = [events getEventById:createdEventId];
                
                EventDetailViewController *dvController = [[EventDetailViewController alloc] initWithNibName:@"EventDetailView" bundle:[NSBundle mainBundle]];
                [dvController setEventInfoData:info];
                [self.navigationController pushViewController:dvController animated:YES];
                [dvController release];
            }
            break;
    }
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
    aRect.size.height -= kbSize.height + 100;
    if (!CGRectContainsPoint(aRect, content.activeField.frame.origin))
    {
        CGPoint scrollPoint = CGPointMake(0.0, content.activeField.frame.origin.y - kbSize.height + 70);
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

@end
