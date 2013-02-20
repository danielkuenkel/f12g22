//
//  User.h
//  cookingPlace
//
//  Created by Daniel Künkel on 22.12.12.
//  Copyright (c) 2012 Daniel Kuenkel. All rights reserved.
//

#import <Foundation/Foundation.h>

@interface User : NSObject

@property (nonatomic, copy) NSString *logonName;
@property (nonatomic, readwrite) int userId;
@property (nonatomic, readwrite) int registered;
@property (nonatomic, readwrite) int activated;
@property (nonatomic, copy) NSString *street;
@property (nonatomic, readwrite) int houseNumber;
@property (nonatomic, readwrite) int zip;
@property (nonatomic, copy) NSString *city;

+(User *)sharedInstance;

@end
