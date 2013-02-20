//
//  EditEventView.h
//  cookingPlace
//
//  Created by Daniel Künkel on 28.12.12.
//  Copyright (c) 2012 Daniel Kuenkel. All rights reserved.
//

#import <UIKit/UIKit.h>
#import "EventInfo.h"

// declare our class
@class EditEventView;

// define the protocol for the delegate
@protocol CustomClassEventDelegate

// define protocol functions that can be used in any class using this delegate
-(void)submitEventData;

@end

@interface EditEventView : UIView
{
    UITextField *activeField;
}

@property (readwrite, nonatomic) BOOL resetInsets;

@property (retain, nonatomic) IBOutlet UITextField *activeField;

@property (retain, nonatomic) IBOutlet UIDatePicker *datePicker;
@property (retain, nonatomic) IBOutlet UITextField *titleInput;
@property (retain, nonatomic) IBOutlet UITextField *personsInput;
@property (retain, nonatomic) IBOutlet UITextField *costInput;
@property (retain, nonatomic) IBOutlet UITextField *streetInput;
@property (retain, nonatomic) IBOutlet UITextField *houseNumberInput;
@property (retain, nonatomic) IBOutlet UITextField *zipInput;
@property (retain, nonatomic) IBOutlet UITextField *cityInput;
@property (retain, nonatomic) IBOutlet UITextField *absractInput;
@property (retain, nonatomic) IBOutlet UIButton *createButton;

@property (nonatomic, assign) UINavigationController * controller;
@property(retain) EventInfo *eventInfoData;

@property (nonatomic, assign) id  delegate;

- (IBAction)onSubmitTap:(id)sender;

- (IBAction)dismissKeyboard:(id)sender;

- (IBAction)textfieldDidBeginEditing:(UITextField *)sender;

-(void)submitEventDataDelegate;

- (BOOL) personsValid;
- (BOOL) maxPersonsValid:(int) currentParticipants;
- (BOOL) costsValid;
- (BOOL) textfieldsValid;

@end