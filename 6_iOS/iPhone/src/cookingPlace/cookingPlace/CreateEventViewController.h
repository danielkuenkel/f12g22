//
//  CreateEventViewController.h
//  cookingPlace
//
//  Created by Daniel Künkel on 19.12.12.
//  Copyright (c) 2012 Daniel Kuenkel. All rights reserved.
//

#import <UIKit/UIKit.h>
#import "EditEventView.h"

@interface CreateEventViewController : UIViewController
{
    EditEventView *content;
    int createdEventId;
}

@property (retain, nonatomic) EditEventView *content;
@property (retain, nonatomic) IBOutlet UIScrollView *scrollView;

@end
