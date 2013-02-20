//
//  MapViewController.h
//  cookingPlace
//
//  Created by Daniel Künkel on 21.12.12.
//  Copyright (c) 2012 Daniel Kuenkel. All rights reserved.
//

#import <UIKit/UIKit.h>
#import <MapKit/MapKit.h>
#import <CoreLocation/CoreLocation.h>

@interface MapViewController : UIViewController <MKMapViewDelegate>

@property (retain, nonatomic) IBOutlet MKMapView *mapView;

@property(nonatomic, assign) id<MKMapViewDelegate> delegate;
@property(nonatomic) BOOL isFirstMapInit;
@property(retain, nonatomic) MKUserLocation *userLocation;
- (IBAction)onLocateClick:(id)sender;

@end
