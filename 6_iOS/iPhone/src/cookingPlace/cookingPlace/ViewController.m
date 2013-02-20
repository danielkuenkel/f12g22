//
//  ViewController.m
//  cookingPlace
//
//  Created by Daniel Künkel on 17.12.12.
//  Copyright (c) 2012 Daniel Kuenkel. All rights reserved.
//

#import "ViewController.h"
#import "SMXMLDocument.h"
#import "User.h"
#import <CommonCrypto/CommonDigest.h>

@interface ViewController ()

@end

@implementation ViewController

- (void)viewDidLoad
{
    [super viewDidLoad];
    
    
    _headline.alpha = 0;
    _username.alpha = 0;
    _password.alpha = 0;
    _loginButton.alpha = 0;
    
    [self animateIn];
	
    //manual set of a custom button background image
    UIImage *buttonImage = [[UIImage imageNamed:@"button-green.png"]
                            resizableImageWithCapInsets:UIEdgeInsetsMake(5, 5, 5, 5)];
    UIImage *buttonImageHighlight = [[UIImage imageNamed:@"button-green-highlight.png"]
                                     resizableImageWithCapInsets:UIEdgeInsetsMake(5, 5, 5, 5)];
    [_loginButton setBackgroundImage:buttonImage forState:UIControlStateNormal];
    [_loginButton setBackgroundImage:buttonImageHighlight forState:UIControlStateHighlighted];
}

- (void)viewDidAppear:(BOOL)animated
{
//    _username.text = @"daniel";
//    _password.text = @"123";
//    [self login];
}

- (void)didReceiveMemoryWarning
{
    [super didReceiveMemoryWarning];
    // Dispose of any resources that can be recreated.
}

- (IBAction)loginButton:(UIButton *)sender {
    
    [self login];
}

-(void) login
{
    NSString *trimedUsername  = [_username.text stringByTrimmingCharactersInSet:[NSCharacterSet whitespaceAndNewlineCharacterSet]];
    NSString *trimedPassword  = [_password.text stringByTrimmingCharactersInSet:[NSCharacterSet whitespaceAndNewlineCharacterSet]];
    
    [self animateToOrigin];
    
    [_username resignFirstResponder];
    [_password resignFirstResponder];
    
    if ([trimedUsername length] > 0 && [trimedPassword length] > 0)
    {
        if([self loginWithData])
        {
            [self performSegueWithIdentifier: @"LoggedInSeque" sender: self];
        }
    }
    else
    {
        UIAlertView *alert = [[UIAlertView alloc] initWithTitle:@"Login" message:@"Please fill in all text fields." delegate:self cancelButtonTitle:@"Close" otherButtonTitles:NULL, nil];
        [alert show];
    }
}

- (IBAction)usernameEditBegin:(id)sender {
    [self animateToTop];
}

- (IBAction)passwordEditBegin:(id)sender {
    [self animateToTop];
}

- (Boolean) loginWithData
{
    NSString *hash = [self MD5HashForString:_password.text];
    NSLog(@"hash: %@", hash);
    NSString *post =[NSString stringWithFormat:@"logon=%@&password=%@", _username.text, _password.text];
    
    NSString *hostStr = @"http://sfsuswe.com/~f12g22/web/php/Login.php?";
    hostStr = [hostStr stringByAppendingString:post];
    NSData *dataURL =  [NSData dataWithContentsOfURL: [ NSURL URLWithString: hostStr ]];
    
    
	// create a new SMXMLDocument with the contents of the php response as XML
    NSError *error;
	SMXMLDocument *document = [SMXMLDocument documentWithData:dataURL error:&error];
    
    // check for errors
    if (error) {
        NSLog(@"Error while parsing the document: %@", error);
        UIAlertView *alert = [[UIAlertView alloc] initWithTitle:@"Login failed" message:@"There was an error. Please try again later." delegate:self cancelButtonTitle:@"Close" otherButtonTitles:NULL, nil];
        [alert show];
        return false;
    }
    
    //parse xml values into singleton User object
    User* user = [User sharedInstance];
    [user setUserId:[[document.root valueWithPath:@"userId"] intValue]];
    [user setLogonName:[document.root valueWithPath:@"logonName"]];
    [user setActivated:[[document.root valueWithPath:@"activate"] intValue]];
    [user setRegistered:[[document.root valueWithPath:@"registered"] intValue]];
    [user setStreet:[document.root valueWithPath:@"street"]];
    [user setHouseNumber:[[document.root valueWithPath:@"houseNumber"] intValue]];
    [user setZip:[[document.root valueWithPath:@"zip"] intValue]];
    [user setCity:[document.root valueWithPath:@"city"]];
    
    //check several activation states and show alerts
    if(user.registered == 1 && user.activated == 1 && user.userId != 0 && user.logonName != NULL)
    {
        return true;
    }
    else if(user.registered == 1 && user.activated == 0)
    {
        UIAlertView *alert = [[UIAlertView alloc] initWithTitle:@"Login fail" message:@"Your account is not activated yet. Please check your email inbox for a mail from Cooking Place." delegate:self cancelButtonTitle:@"Close" otherButtonTitles:NULL, nil];
        [alert show];
    }
    else
    {
        UIAlertView *alert = [[UIAlertView alloc] initWithTitle:@"Login failed" message:@"Please check your inputs!" delegate:self cancelButtonTitle:@"Close" otherButtonTitles:NULL, nil];
        [alert show];
    }
    
    return false;
}

-(void)animateIn
{
    [UIView beginAnimations:@"animateIn" context:NULL];
    [UIView setAnimationDuration:1.0];
    _headline.alpha = 1;
    _username.alpha = .7;
    _password.alpha = .7;
    _loginButton.alpha = 1;
    [UIView commitAnimations];
}

-(void)animateToTop
{
    [UIView beginAnimations:@"animateToTop" context:NULL];
    _headline.alpha = 0;
    _username.alpha = 1;
    _password.alpha = 1;
    _headline.frame = CGRectMake(_headline.frame.origin.x,
                                 40,
                                 _headline.frame.size.width,
                                 _headline.frame.size.height);
    _username.frame = CGRectMake(_username.frame.origin.x,
                                 68,
                                 _username.frame.size.width,
                                 _username.frame.size.height);
    _password.frame = CGRectMake(_password.frame.origin.x,
                                 116,
                                 _password.frame.size.width,
                                 _password.frame.size.height);
    _loginButton.frame = CGRectMake(_loginButton.frame.origin.x,
                                    164,
                                    _loginButton.frame.size.width,
                                    _loginButton.frame.size.height);
    [UIView commitAnimations];
}

-(void)animateToOrigin
{
    [UIView beginAnimations:@"animateToOrigin" context:NULL];
    _headline.alpha = 1;
    _username.alpha = .7;
    _password.alpha = .7;
    _headline.frame = CGRectMake(_headline.frame.origin.x,
                                 75,
                                 _headline.frame.size.width,
                                 _headline.frame.size.height);
    _username.frame = CGRectMake(_username.frame.origin.x,
                                 160,
                                 _username.frame.size.width,
                                 _username.frame.size.height);
    _password.frame = CGRectMake(_password.frame.origin.x,
                                 208,
                                 _password.frame.size.width,
                                 _password.frame.size.height);
    _loginButton.frame = CGRectMake(_loginButton.frame.origin.x,
                                    256,
                                    _loginButton.frame.size.width,
                                    _loginButton.frame.size.height);
    [UIView commitAnimations];
    
}


- (void)dealloc {
    [_loginButton release];
    [_headline release];
    [super dealloc];
}

- (NSString *)MD5HashForString:(NSString *)input {
    unsigned char result[CC_MD5_DIGEST_LENGTH];
    
    // Convert NSString into C-string and generate MD5 Hash
    CC_MD5([input UTF8String], [input length], result);
    
    // Convert C-string (the hash) into NSString
    NSMutableString *hash = [NSMutableString string];
    
    for (int i = 0; i < CC_MD5_DIGEST_LENGTH; i++) {
        [hash appendFormat:@"%02X", result[i]];
    }
    
    return [hash lowercaseString];
}

@end
