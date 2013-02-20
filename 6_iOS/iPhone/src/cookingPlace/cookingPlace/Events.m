//
//  User.m
//  cookingPlace
//
//  Created by Daniel Künkel on 22.12.12.
//  Copyright (c) 2012 Daniel Kuenkel. All rights reserved.
//

#import "Events.h"
#import "SMXMLDocument.h"
#import "EventInfo.h"
#import "EventParticipant.h"
#import "User.h"

@implementation Events

@synthesize logonName;
@synthesize userId;
@synthesize registered;
@synthesize activated;
@synthesize street;
@synthesize houseNumber;
@synthesize zip;
@synthesize city;

static Events *sharedInstance = nil;

// Get the shared instance and create it if necessary.
+ (Events *)sharedInstance {
    if (sharedInstance == nil) {
        sharedInstance = [[super allocWithZone:NULL] init];
    }
    
    return sharedInstance;
}

// We can still have a regular init method, that will get called the first time the Singleton is used.
- (id)init
{
    self = [super init];
    
    if (self) {
        // Work your initialising magic here as you normally would
    }
    
    return self;
}

// Your dealloc method will never be called, as the singleton survives for the duration of your app.
// However, I like to include it so I know what memory I'm using (and incase, one day, I convert away from Singleton).
-(void)dealloc
{
    // I'm never called!
    [super dealloc];
}

// We don't want to allocate a new instance, so return the current one.
+ (id)allocWithZone:(NSZone*)zone {
    return [[self sharedInstance] retain];
}

// Equally, we don't want to generate multiple copies of the singleton.
- (id)copyWithZone:(NSZone *)zone {
    return self;
}

// Once again - do nothing, as we don't have a retain counter for this object.
- (id)retain {
    return self;
}

// Replace the retain counter so we can never release this object.
- (NSUInteger)retainCount {
    return NSUIntegerMax;
}

// This function is empty, as we don't want to let the user release this object.
- (oneway void)release {
    
}

//Do nothing, other than return the shared instance - as this is expected from autorelease.
- (id)autorelease {
    return self;
}


-(NSMutableArray*) getAllEvents
{
    User *user = [User sharedInstance];
    NSString *post =[NSString stringWithFormat:@"type=all&sessionUser=%i&zipcode=", user.userId];
    
    NSString *hostStr = @"http://sfsuswe.com/~f12g22/web/php/SearchEvent.php?";
    hostStr = [hostStr stringByAppendingString:post];
    NSData *dataURL =  [NSData dataWithContentsOfURL: [ NSURL URLWithString: hostStr ]];
    
    
	// create a new SMXMLDocument with the contents of the php response as XML
    NSError *error;
	SMXMLDocument *document = [SMXMLDocument documentWithData:dataURL error:&error];
    
    // check for errors
    if (error) {
        NSLog(@"Error while parsing the document: %@", error);
        UIAlertView *alert = [[UIAlertView alloc] initWithTitle:@"Login fail" message:@"There was an error. Please try again later!" delegate:self cancelButtonTitle:@"close" otherButtonTitles:NULL, nil];
        [alert show];
    }
    
    // Array of objects that we are returning
    NSMutableArray *result = [[NSMutableArray alloc] init];
    
    // Get the videos node
    SMXMLElement *events = document.root;
    
    // Go through every sub-element "event"
    for (SMXMLElement *event in [events childrenNamed:@"event"]) {
        EventInfo *info = [[EventInfo alloc] init];
        info.eventId = [[event valueWithPath:@"id"] intValue];
        info.title = [event valueWithPath:@"title"];
        info.abstract = [event valueWithPath:@"abstract"];
        info.ownerId = [[event valueWithPath:@"user_id"] intValue];
        info.ownerName = [event valueWithPath:@"logon_name"];
        info.isOwner = [[event valueWithPath:@"isOwner"] intValue] == 0 ? 1 : 0;
        info.maxParticipants = [[event valueWithPath:@"maxParticipants"] intValue];
        info.cost = [[event valueWithPath:@"cost"] floatValue];
        info.street = [event valueWithPath:@"street"];
        info.houseNumber = [event valueWithPath:@"houseNumber"];
        info.zip = [event valueWithPath:@"zip"];
        info.city = [event valueWithPath:@"city"];
        int timestamp = [[event valueWithPath:@"timestamp"] intValue];
        info.date = [NSDate dateWithTimeIntervalSince1970:timestamp];
        [result addObject:info];
        
        NSMutableArray *participantsArr = [[[NSMutableArray alloc] init] autorelease];
        
        for (SMXMLElement *participants in [event childrenNamed:@"participants"]) {
            for (SMXMLElement *participant in [participants childrenNamed:@"participant"]) {
                EventParticipant *participantObj = [[EventParticipant alloc] init];
                participantObj.name = [participant valueWithPath:@"name"];
                [participantsArr addObject:participantObj];
            }
        }
        info.participants = participantsArr;
        
        [info release];
    }
    allEvents = result;
    return result;
}

-(NSMutableArray*) searchEvents:(NSString*) searchKey
{
    NSMutableArray *result = [[NSMutableArray alloc] init];
    for (EventInfo *info in allEvents) {
        if ([info.zip rangeOfString:searchKey].location == NSNotFound) {
        } else {
            [result addObject:info];
            [info release];
        }
    }
    return result;
}

-(EventInfo*) getEventById:(int) eventId
{
    for (EventInfo *info in allEvents) {
        if(info.eventId == eventId)
        {
            return info;
        }
    }
    return nil;
}

@end
