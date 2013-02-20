//
//  TestListViewController.m
//  cookingPlace
//
//  Created by Daniel Künkel on 25.12.12.
//  Copyright (c) 2012 Daniel Kuenkel. All rights reserved.
//

#import "EventListViewController.h"
#import "Events.h"
#import "EventInfo.h"
#import "EventListCell.h"
#import "EventDetailViewController.h"
#import "SMXMLDocument.h"

@interface EventListViewController ()

@end

@implementation EventListViewController

- (id)initWithStyle:(UITableViewStyle)style
{
    self = [super initWithStyle:style];
    if (self) {
        // Custom initialization
    }
    return self;
}

- (void)viewWillAppear:(BOOL)animated
{
    Events* events = [Events sharedInstance];
    allEvents = [events getAllEvents];
    [self.tableView reloadData];
}

-(void)viewDidDisappear:(BOOL)animated
{
}

- (void)viewDidLoad
{
    [_searchInput setShowsCancelButton:YES];
    for (UIView *subView in _searchInput.subviews){
        if([subView isKindOfClass:[UIButton class]]){
            [(UIButton*)subView setTitle:@"Reset" forState:UIControlStateNormal];
        }
    }
    
    [super viewDidLoad];
}

- (void)didReceiveMemoryWarning
{
    [super didReceiveMemoryWarning];
    // Dispose of any resources that can be recreated.
}

#pragma mark - Table view data source

- (NSInteger)numberOfSectionsInTableView:(UITableView *)tableView
{
    return 1;
}

- (NSInteger)tableView:(UITableView *)tableView numberOfRowsInSection:(NSInteger)section
{
    return [allEvents count];
}

- (UITableViewCell *)tableView:(UITableView *)tableView cellForRowAtIndexPath:(NSIndexPath *)indexPath
{
    static NSString *cellId = @"EventListCell";
    
    EventListCell *cell = (EventListCell *)[self.tableView dequeueReusableCellWithIdentifier:cellId];
    if (cell == nil) {
        cell = [[EventListCell alloc] initWithStyle:UITableViewCellStyleDefault reuseIdentifier:cellId];
    }
    
    EventInfo *info = [allEvents objectAtIndex:indexPath.row];
    cell.title.text = info.title;

    NSDateFormatter *formatter = [[[NSDateFormatter alloc] init] autorelease];
    [formatter setTimeZone:[NSTimeZone timeZoneWithName:@"UTC"]];
    [formatter setDateFormat:@"dd.MM.yyyy 'at' HH:mm"];
    NSString *stringFromDate = [formatter stringFromDate:info.date];

    
    cell.date.text = stringFromDate;
    cell.abstract.text = [NSString stringWithFormat:@"%@ says: %@", info.ownerName, info.abstract];
    cell.subtitle.text = [NSString stringWithFormat:@"%@ %@, %@ %@", info.street, info.houseNumber, info.zip, info.city];
    
    return cell;
}

/*
// Override to support conditional editing of the table view.
- (BOOL)tableView:(UITableView *)tableView canEditRowAtIndexPath:(NSIndexPath *)indexPath
{
    // Return NO if you do not want the specified item to be editable.
    return YES;
}
*/

/*
// Override to support editing the table view.
- (void)tableView:(UITableView *)tableView commitEditingStyle:(UITableViewCellEditingStyle)editingStyle forRowAtIndexPath:(NSIndexPath *)indexPath
{
    if (editingStyle == UITableViewCellEditingStyleDelete) {
        // Delete the row from the data source
        [tableView deleteRowsAtIndexPaths:@[indexPath] withRowAnimation:UITableViewRowAnimationFade];
    }   
    else if (editingStyle == UITableViewCellEditingStyleInsert) {
        // Create a new instance of the appropriate class, insert it into the array, and add a new row to the table view
    }   
}
*/

/*
// Override to support rearranging the table view.
- (void)tableView:(UITableView *)tableView moveRowAtIndexPath:(NSIndexPath *)fromIndexPath toIndexPath:(NSIndexPath *)toIndexPath
{
}
*/

/*
// Override to support conditional rearranging of the table view.
- (BOOL)tableView:(UITableView *)tableView canMoveRowAtIndexPath:(NSIndexPath *)indexPath
{
    // Return NO if you do not want the item to be re-orderable.
    return YES;
}
*/

#pragma mark - Table view delegate

- (void)tableView:(UITableView *)tableView didSelectRowAtIndexPath:(NSIndexPath *)indexPath
{
    [self dismissKeyboard];
    
    EventInfo *info = [allEvents objectAtIndex:indexPath.row];
    
    EventDetailViewController *dvController = [[EventDetailViewController alloc] initWithNibName:@"EventDetailView" bundle:[NSBundle mainBundle]];
    [dvController setEventInfoData:info];
    [self.navigationController pushViewController:dvController animated:YES];
    [dvController release];
}


-(void) scrollViewDidScroll:(UIScrollView *)scrollView
{
    [self dismissKeyboard];
}

-(void)dismissKeyboard {
    [_searchInput resignFirstResponder];
}

- (void)searchBarSearchButtonClicked:(UISearchBar *)searchBar
{
    [self dismissKeyboard];
    Events* events = [Events sharedInstance];
    allEvents = [events searchEvents:searchBar.text];
    [self.tableView reloadData];
}

- (void)searchBar:(UISearchBar *)searchBar textDidChange:(NSString *)searchKey
{
    Events* events = [Events sharedInstance];
    allEvents = [events searchEvents:searchKey];
    [self.tableView reloadData];
    
}

- (void)searchBarCancelButtonClicked:(UISearchBar *)searchBar
{
    [self dismissKeyboard];
    _searchInput.text = @"";
    Events* events = [Events sharedInstance];
    allEvents = [events getAllEvents];
    [self.tableView reloadData];
}

-(BOOL)tableView:(UITableView *)tableView canEditRowAtIndexPath:(NSIndexPath *)indexPath
{
    EventInfo *info = [allEvents objectAtIndex:indexPath.row];
    if(info.isOwner == 1)
    {
        return YES;
    }
    return NO;
}

- (void)tableView:(UITableView *)tableView commitEditingStyle:(UITableViewCellEditingStyle)editingStyle
forRowAtIndexPath:(NSIndexPath *)indexPath {
    // If row is deleted, remove it from the list.
    EventInfo *info = [allEvents objectAtIndex:indexPath.row];
    
    if (editingStyle == UITableViewCellEditingStyleDelete) {
        UIActionSheet *actionSheet = [[UIActionSheet alloc] initWithTitle:@"Are you sure you want to cancel this event?"
                                                                 delegate:self
                                                        cancelButtonTitle:@"Cancel"
                                                   destructiveButtonTitle:@"OK"
                                                        otherButtonTitles: nil];
        
        deleteEventId = info.eventId;
        actionSheet.actionSheetStyle = UIActionSheetStyleBlackOpaque;
        [actionSheet showInView:self.parentViewController.parentViewController.view];
        [actionSheet release];
    }
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
    NSString *post = [NSString stringWithFormat:@"eventId=%d", deleteEventId];
    
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
    
    Events *events = [Events sharedInstance];
    allEvents = [events getAllEvents];
    [self.tableView reloadData];
    [self.tableView scrollRectToVisible:CGRectMake(0, 0, 1, 1) animated:YES];
}

@end
