//
//  EditEventViewController.h
//  cookingPlace
//
//  Created by Daniel Künkel on 28.12.12.
//  Copyright (c) 2012 Daniel Kuenkel. All rights reserved.
//

#import <UIKit/UIKit.h>
#import "EventInfo.h"
#import "EditEventView.h"

@interface EditEventViewController : UIViewController <UIScrollViewDelegate>
{
    EditEventView *content;
}
@property(retain) EventInfo *eventInfoData;
@property (retain, nonatomic) EditEventView *content;
@property (retain, nonatomic) IBOutlet UIScrollView *scrollView;

@end
