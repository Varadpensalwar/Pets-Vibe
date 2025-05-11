import 'package:firebase_auth/firebase_auth.dart';
import 'package:firebase_core/firebase_core.dart';
import 'package:flutter/material.dart';
import 'package:pets_vibe/screens/Onboarding%20Screen.dart';
import 'package:shared_preferences/shared_preferences.dart';

import 'package:pets_vibe/screens/ContactUsScreen.dart';
import 'package:pets_vibe/screens/about_us_screen.dart';
import 'package:pets_vibe/screens/home_screen.dart';
import 'package:pets_vibe/screens/login_screen.dart';
import 'package:pets_vibe/screens/mood_detection.dart';
import 'package:pets_vibe/screens/ngo_screen.dart';
import 'package:pets_vibe/screens/self_dignose.dart';

void main() async {
  WidgetsFlutterBinding.ensureInitialized();
  await Firebase.initializeApp();

  final prefs = await SharedPreferences.getInstance();
  final onboardingDone = prefs.getBool('onboarding_done') ?? false;

  runApp(MyApp(onboardingDone: onboardingDone));
}

class MyApp extends StatelessWidget {
  final bool onboardingDone;

  const MyApp({super.key, required this.onboardingDone});

  @override
  Widget build(BuildContext context) {
    return MaterialApp(
      title: 'Pets Vibe',
      theme: ThemeData(primarySwatch: Colors.blue),
      debugShowCheckedModeBanner: false,
      routes: {
        '/home': (context) => const HomeScreen(),
        '/ngos': (context) => const NGOScreen(),
        '/self-diagnose': (context) => const PetDiseaseDetectorScreen(),
        '/mood-detection': (context) => const MoodDetectionScreen(),
        '/about-us': (context) => const AboutUsScreen(),
        '/contact': (context) => const ContactUsScreen(),
      },
      home: onboardingDone ? const AuthCheck() : const OnboardingScreen(),
    );
  }
}

class AuthCheck extends StatelessWidget {
  const AuthCheck({super.key});

  @override
  Widget build(BuildContext context) {
    return StreamBuilder<User?>(
      stream: FirebaseAuth.instance.authStateChanges(),
      builder: (context, snapshot) {
        if (snapshot.connectionState == ConnectionState.active) {
          if (snapshot.hasData) {
            return const HomeScreen();
          } else {
            return const LoginScreen();
          }
        }
        return const Center(child: CircularProgressIndicator());
      },
    );
  }
}
