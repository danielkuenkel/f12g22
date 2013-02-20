//
//  EventListCell.h
//  cookingPlace
//
//  Created by Daniel Künkel on 24.12.12.
//  Copyright (c) 2012 Daniel Kuenkel. All rights reserved.
//

#import <UIKit/UIKit.h>

@interface EventListCell : UITableViewCell

@property (strong, nonatomic) IBOutlet UILabel *title;
@property (strong, nonatomic) IBOutlet UILabel *date;
@property (strong, nonatomic) IBOutlet UILabel *subtitle;
@property (retain, nonatomic) IBOutlet UILabel *abstract;

@end
