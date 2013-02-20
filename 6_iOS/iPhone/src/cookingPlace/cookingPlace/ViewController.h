//
//  ViewController.h
//  cookingPlace
//
//  Created by Daniel Künkel on 17.12.12.
//  Copyright (c) 2012 Daniel Kuenkel. All rights reserved.
//

#import <UIKit/UIKit.h>

@interface ViewController : UIViewController

@property (strong, nonatomic) IBOutlet UITextField *username;
@property (strong, nonatomic) IBOutlet UITextField *password;
@property (retain, nonatomic) IBOutlet UIButton *loginButton;
@property (retain, nonatomic) IBOutlet UILabel *headline;

- (IBAction)loginButton:(UIButton *)sender;
- (IBAction)usernameEditBegin:(id)sender;
- (IBAction)passwordEditBegin:(id)sender;

@end
