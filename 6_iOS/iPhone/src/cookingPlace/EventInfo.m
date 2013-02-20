//
//  EventInfo.m
//  cookingPlace
//
//  Created by Daniel Künkel on 21.12.12.
//  Copyright (c) 2012 Daniel Kuenkel. All rights reserved.
//

#import "EventInfo.h"
#import "EventParticipant.h"

@implementation EventInfo

-(NSString*) getAllParticipants;
{
    NSString *participantString = [NSString stringWithFormat:@"%@ (creator)", _ownerName];
    
    for (EventParticipant *participant in _participants) {
        if(![participant.name isEqualToString:_ownerName])
        {
            participantString = [participantString stringByAppendingFormat:@", %@", participant.name];
        }
    }
    return participantString;
}

-(BOOL) hasJoinedEvent:(NSString*) userName
{
    for (EventParticipant *participant in _participants) {
        if([participant.name isEqualToString:userName])
        {
            return YES;
        }
    }
    return NO;
}

@end