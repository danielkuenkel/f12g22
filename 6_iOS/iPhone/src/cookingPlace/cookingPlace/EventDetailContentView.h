//
//  EventDetailContentView.h
//  cookingPlace
//
//  Created by Daniel Künkel on 28.12.12.
//  Copyright (c) 2012 Daniel Kuenkel. All rights reserved.
//

#import <UIKit/UIKit.h>
#import "EventInfo.h"
#import <MapKit/MapKit.h>

// declare our class
@class EventDetailContentView;

// define the protocol for the delegate
@protocol CustomClassDelegate

// define protocol functions that can be used in any class using this delegate
-(void)showEditEventForm;

@end

@interface EventDetailContentView : UIView <UIActionSheetDelegate>
{    
    IBOutlet MKMapView *mapView;
    
    IBOutlet UILabel *eventTitle;
    IBOutlet UILabel *abstract;
    IBOutlet UILabel *createdBy;
    IBOutlet UILabel *participants;
    IBOutlet UILabel *cost;
    IBOutlet UILabel *places;
    IBOutlet UILabel *adress;
    IBOutlet UILabel *date;
    
    IBOutlet UIButton *cancelButton;
    IBOutlet UIButton *editButton;
    IBOutlet UIButton *joinButton;
    
    UINavigationController *controller;
}
@property (nonatomic, assign) id  delegate;
@property (nonatomic, assign) UINavigationController * controller;
@property(retain) EventInfo *eventInfoData;

- (IBAction)onEditTap:(UIButton *)sender;
- (IBAction)onCancelTap:(UIButton *)sender;
- (IBAction)onJoinTap:(UIButton *)sender;

-(void)showEditEventFormDelegate;

@end
