//
//  EventListCell.m
//  cookingPlace
//
//  Created by Daniel Künkel on 24.12.12.
//  Copyright (c) 2012 Daniel Kuenkel. All rights reserved.
//

#import "EventListCell.h"

@implementation EventListCell

@synthesize title;
@synthesize date;
@synthesize abstract;
@synthesize subtitle;

- (id)initWithStyle:(UITableViewCellStyle)style reuseIdentifier:(NSString *)reuseIdentifier
{
    self = [super initWithStyle:style reuseIdentifier:reuseIdentifier];
    if (self) {
//        NSArray *nibArray = [[NSBundle mainBundle] loadNibNamed:@"EventListCell" owner:self options:nil];
//        self = [nibArray objectAtIndex:0];
    }
    return self;
}

- (void)setSelected:(BOOL)selected animated:(BOOL)animated
{
    [super setSelected:selected animated:animated];

    // Configure the view for the selected state
}

- (void)setEditing:(BOOL)editing animated:(BOOL)animate
{
    [super setEditing:editing animated:animate];
    
    if(editing) {
        [UIView beginAnimations:nil context:nil];
        [UIView setAnimationDuration:0.3];
        date.alpha = 0;
        
        CGRect f = abstract.frame;
        f.size.width = 220;
        abstract.frame = f;
        
        f = subtitle.frame;
        f.size.width = 220;
        subtitle.frame = f;
        
        [UIView commitAnimations];
    } else {
        [UIView beginAnimations:nil context:nil];
        [UIView setAnimationDuration:0.3];
        date.alpha = 1;
        [UIView commitAnimations];
    }
}


- (void)dealloc {
    [title release];
    [date release];
    [subtitle release];
    [abstract release];
    [super dealloc];
}

@end
