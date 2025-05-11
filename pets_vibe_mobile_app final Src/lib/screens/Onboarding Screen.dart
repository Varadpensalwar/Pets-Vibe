import 'package:flutter/material.dart';
import 'package:shared_preferences/shared_preferences.dart';
import 'login_screen.dart'; // Replace with your actual login screen import

class OnboardingScreen extends StatefulWidget {
  const OnboardingScreen({super.key});

  @override
  _OnboardingScreenState createState() => _OnboardingScreenState();
}

class _OnboardingScreenState extends State<OnboardingScreen> {
  final PageController _controller = PageController();
  bool isLastPage = false;

  // Pages data
  final List<Map<String, String>> onboardingData = [
    {
      'image': 'assets/welcome.png',
      'title': 'Welcome to Pets Vibe!',
      'description':
          'Your all-in-one app for easier pet care. Detect diseases, shop products, and understand your pets mood—empowering pet owners with the right tools for the best care.',
    },
    {
      'image': 'assets/onboarding1.png',
      'title': 'Detect Pet Diseases',
      'description':
          'Use our smart detection system to diagnose pet health issues instantly by uploading a photo—fast, reliable, and hassle-free.',
    },
    {
      'image': 'assets/onboarding2.png',
      'title': 'Shop Pet Products',
      'description':
          'Find everything your pet needs—from food and grooming to toys and accessories, all available at your fingertips.',
    },
    {
      'image': 'assets/onboarding3.png',
      'title': 'Mood Detection',
      'description':
          'Unlock your pet’s emotions with advanced mood detection technology. Understand how your pet is feeling, whether they’re happy, Sad, or Angry, and take better care of their mental well-being.',
    },
    {
      'image': 'assets/onboarding4.png',
      'title': 'Meet the Team',
      'description':
          'Who is behind Pets Vibe? Our Team: Dattatray Dharmoji , Sayali Patil, Varad Pensalwar, and Gayatri Patkure. Together, we bring you a better way to care for your pets!',
    },
  ];

  Future<void> _completeOnboarding() async {
    final prefs = await SharedPreferences.getInstance();
    await prefs.setBool('onboarding_done', true);
    Navigator.pushReplacement(
      context,
      MaterialPageRoute(builder: (_) => const LoginScreen()),
    );
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      backgroundColor: Colors.teal,
      body: Container(
        decoration: const BoxDecoration(
          gradient: LinearGradient(
            colors: [
              Color.fromARGB(255, 255, 254, 253), // Teal
              Color.fromARGB(255, 248, 201, 125), // Light Teal
              Color.fromARGB(255, 255, 153, 0), // Cyan
            ],
            begin: Alignment.topCenter,
            end: Alignment.bottomCenter,
          ),
        ),
        child: Padding(
          padding: const EdgeInsets.all(16.0),
          child: Column(
            children: [
              Expanded(
                child: PageView(
                  controller: _controller,
                  onPageChanged: (index) {
                    setState(() {
                      isLastPage = (index == onboardingData.length - 1);
                    });
                  },
                  children: List.generate(onboardingData.length, (index) {
                    return Column(
                      mainAxisAlignment: MainAxisAlignment.center,
                      children: [
                        Image.asset(
                          onboardingData[index]['image']!,
                          width: 250,
                          height: 250,
                        ),
                        const SizedBox(height: 40),
                        Text(
                          onboardingData[index]['title']!,
                          style: const TextStyle(
                            color: Color.fromARGB(255, 253, 253, 253),
                            fontSize: 28,
                            fontWeight: FontWeight.bold,
                          ),
                          textAlign: TextAlign.center,
                        ),
                        const SizedBox(height: 20),
                        Padding(
                          padding: const EdgeInsets.symmetric(horizontal: 24.0),
                          child: Text(
                            onboardingData[index]['description']!,
                            style: const TextStyle(
                              color: Color.fromARGB(255, 255, 254, 254),
                              fontSize: 16,
                              fontStyle: FontStyle.italic,
                            ),
                            textAlign: TextAlign.center,
                          ),
                        ),
                      ],
                    );
                  }),
                ),
              ),
              Padding(
                padding: const EdgeInsets.symmetric(horizontal: 16.0),
                child: Row(
                  mainAxisAlignment: MainAxisAlignment.spaceBetween,
                  children: [
                    if (!isLastPage)
                      GestureDetector(
                        onTap: () {
                          _controller.jumpToPage(onboardingData.length - 1);
                        },
                        child: const Text(
                          'Skip',
                          style: TextStyle(
                            color: Color.fromARGB(255, 253, 252, 252),
                            fontSize: 16,
                          ),
                        ),
                      ),
                    ElevatedButton(
                      onPressed: () {
                        if (isLastPage) {
                          _completeOnboarding(); // Save to SharedPreferences
                        } else {
                          _controller.nextPage(
                            duration: const Duration(milliseconds: 300),
                            curve: Curves.easeIn,
                          );
                        }
                      },
                      style: ElevatedButton.styleFrom(
                        padding: const EdgeInsets.symmetric(
                          vertical: 12.0,
                          horizontal: 24.0,
                        ),
                        shape: RoundedRectangleBorder(
                          borderRadius: BorderRadius.circular(8),
                        ),
                      ),
                      child: Text(isLastPage ? 'Get Started' : 'Next'),
                    ),
                  ],
                ),
              ),
              const SizedBox(height: 40),
            ],
          ),
        ),
      ),
    );
  }
}
