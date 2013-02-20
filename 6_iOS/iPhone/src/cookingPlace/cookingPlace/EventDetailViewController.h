//
//  EventDetailViewController.h
//  cookingPlace
//
//  Created by Daniel Künkel on 24.12.12.
//  Copyright (c) 2012 Daniel Kuenkel. All rights reserved.
//

#import <UIKit/UIKit.h>
#import "EventInfo.h"
#import <EventKit/EventKit.h>

@interface EventDetailViewController : UIViewController <UIScrollViewDelegate, UIActionSheetDelegate>
{
    EventInfo *eventInfoData;
    EKEventStore *eventStoreAccess;
}
@property (retain, nonatomic) IBOutlet UIScrollView *scrollView;

-(void)setEventInfoData:(EventInfo *)infoData;
-(EventInfo *)eventInfoData;

@end
