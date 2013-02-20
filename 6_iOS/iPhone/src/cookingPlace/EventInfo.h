//
//  EventInfo.h
//  cookingPlace
//
//  Created by Daniel Künkel on 21.12.12.
//  Copyright (c) 2012 Daniel Kuenkel. All rights reserved.
//

#import <Foundation/Foundation.h>

@interface EventInfo : NSObject
@property (readwrite) int eventId;
@property (retain) NSString *title;
@property (retain) NSString *abstract;
@property (retain) NSString *street;
@property (retain) NSString *houseNumber;
@property (retain) NSString *zip;
@property (retain) NSString *city;
@property (nonatomic, assign) int ownerId;
@property (retain) NSString *ownerName;
@property (readwrite) int isOwner;
@property (readwrite) int maxParticipants;
@property (readwrite) float cost;
@property (nonatomic, strong) NSDate *date;
@property (nonatomic, strong) NSArray *participants;

-(NSString*) getAllParticipants;
-(BOOL) hasJoinedEvent:(NSString*) userName;

@end
