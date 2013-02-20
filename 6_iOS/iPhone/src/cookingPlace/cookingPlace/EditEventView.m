//
//  EditEventView.m
//  cookingPlace
//
//  Created by Daniel Künkel on 28.12.12.
//  Copyright (c) 2012 Daniel Kuenkel. All rights reserved.
//

#import "EditEventView.h"
#import <QuartzCore/QuartzCore.h>
#import "SMXMLDocument.h"
#import "User.h"

@implementation EditEventView
@synthesize activeField;
@synthesize delegate;

- (id)initWithFrame:(CGRect)frame
{
    self = [super initWithFrame:frame];
    if (self) {
        // Initialization code
    }
    return self;
}

-(void)sizeToFit
{
    // set pattern background
    self.backgroundColor = [[UIColor alloc] initWithPatternImage:[UIImage imageNamed:@"black_linen_v2.png"]];
    
	
    UIImage *buttonImage = [[UIImage imageNamed:@"button-green.png"]
                            resizableImageWithCapInsets:UIEdgeInsetsMake(5, 5, 5, 5)];
    UIImage *buttonImageHighlight = [[UIImage imageNamed:@"button-green-highlight.png"]
                                     resizableImageWithCapInsets:UIEdgeInsetsMake(5, 5, 5, 5)];
    [_createButton setBackgroundImage:buttonImage forState:UIControlStateNormal];
    [_createButton setBackgroundImage:buttonImageHighlight forState:UIControlStateHighlighted];
    
    [[_datePicker layer] setMasksToBounds:NO];
    [[_datePicker layer] setShadowOffset:CGSizeMake(0, 0)];
    [[_datePicker layer] setShadowColor:[[UIColor blackColor] CGColor]];
    [[_datePicker layer] setShadowRadius:10.0];
    [[_datePicker layer] setShadowOpacity:1];
    _datePicker.layer.shadowPath = [UIBezierPath bezierPathWithRect:_datePicker.bounds].CGPath;
}


- (IBAction)onSubmitTap:(id)sender {
    [self dismissKeyboard:nil];
    [self submitEventDataDelegate];
}

-(void)submitEventDataDelegate
{
    [delegate submitEventData];
}

- (IBAction)dismissKeyboard:(id)sender
{
    [_titleInput becomeFirstResponder];
    [_titleInput resignFirstResponder];
    activeField = nil;
}

- (IBAction)textfieldDidBeginEditing:(UITextField *)sender {
    activeField = sender;
}

-(BOOL)personsValid
{
    NSCharacterSet *alphaNums = [NSCharacterSet decimalDigitCharacterSet];
    NSCharacterSet *inStringSet = [NSCharacterSet characterSetWithCharactersInString:_personsInput.text];
    int persons = [_personsInput.text intValue];
    if([_personsInput.text length] > 0 && [alphaNums isSupersetOfSet:inStringSet] && persons >= 2 && persons < 100)
    {
        return YES;
    }
    
    return NO;
}

-(BOOL)maxPersonsValid:(int) currentParticipants
{
    if(currentParticipants <= [_personsInput.text intValue])
    {
        return YES;
    }
    
    return NO;
}

-(BOOL)costsValid
{
    NSCharacterSet *alphaNums = [NSCharacterSet characterSetWithCharactersInString:@"0123456789."];
    NSCharacterSet *inStringSet = [NSCharacterSet characterSetWithCharactersInString:_costInput.text];
    if ([_costInput.text length] > 0 && [alphaNums isSupersetOfSet:inStringSet]) {
        return YES;
    }
    
    return NO;
}

- (BOOL) textfieldsValid
{
    NSString *trimmedTitle  = [_titleInput.text stringByTrimmingCharactersInSet:[NSCharacterSet whitespaceAndNewlineCharacterSet]];
    NSString *trimmedPersons  = [_personsInput.text stringByTrimmingCharactersInSet:[NSCharacterSet whitespaceAndNewlineCharacterSet]];
    NSString *trimmedCost = [_costInput.text stringByTrimmingCharactersInSet:[NSCharacterSet whitespaceAndNewlineCharacterSet]];
    NSString *trimmedStreet = [_streetInput.text stringByTrimmingCharactersInSet:[NSCharacterSet whitespaceAndNewlineCharacterSet]];
    NSString *trimmedNr = [_houseNumberInput.text stringByTrimmingCharactersInSet:[NSCharacterSet whitespaceAndNewlineCharacterSet]];
    NSString *trimmedZip = [_zipInput.text stringByTrimmingCharactersInSet:[NSCharacterSet whitespaceAndNewlineCharacterSet]];
    NSString *trimmedCity = [_cityInput.text stringByTrimmingCharactersInSet:[NSCharacterSet whitespaceAndNewlineCharacterSet]];
    NSString *trimmedAbstract = [_absractInput.text stringByTrimmingCharactersInSet:[NSCharacterSet whitespaceAndNewlineCharacterSet]];
    
    if([trimmedTitle length] > 0 &&
       [trimmedPersons length] > 0 &&
       [trimmedCost length] > 0 &&
       [trimmedStreet length] > 0 &&
       [trimmedNr length] > 0 &&
       [trimmedZip length] > 0 &&
       [trimmedCity length] > 0 &&
       [trimmedAbstract length] > 0)
    {
        return true;
    }
    
    return false;
}

-(void)dealloc
{
    [_datePicker release];
    [_titleInput release];
    [_personsInput release];
    [_costInput release];
    [_streetInput release];
    [_houseNumberInput release];
    [_zipInput release];
    [_cityInput release];
    [_absractInput release];
    [_createButton release];
    
    [super dealloc];
}
@end
