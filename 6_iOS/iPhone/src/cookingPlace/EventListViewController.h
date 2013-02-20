//
//  TestListViewController.h
//  cookingPlace
//
//  Created by Daniel Künkel on 25.12.12.
//  Copyright (c) 2012 Daniel Kuenkel. All rights reserved.
//

#import <UIKit/UIKit.h>

@interface EventListViewController : UITableViewController <UIActionSheetDelegate>
{
    NSMutableArray* allEvents;
    int deleteEventId;
}

@property (retain, nonatomic) IBOutlet UISearchBar *searchInput;

@end
