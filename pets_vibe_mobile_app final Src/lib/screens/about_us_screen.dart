import 'package:flutter/material.dart';
import 'package:pets_vibe/widget/app_drawer.dart';

class AboutUsScreen extends StatelessWidget {
  const AboutUsScreen({super.key});

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      drawer: AppDrawer(),
      backgroundColor: Colors.grey[100],
      appBar: AppBar(
        title: const Text('About Us'),
        backgroundColor: const Color.fromARGB(255, 209, 210, 210),
        centerTitle: true,
      ),
      body: Padding(
        padding: const EdgeInsets.all(16.0),
        child: ListView(
          children: [
            // App Info Card
            Card(
              shape: RoundedRectangleBorder(
                borderRadius: BorderRadius.circular(16),
              ),
              elevation: 4,
              child: Padding(
                padding: const EdgeInsets.all(20.0),
                child: Column(
                  crossAxisAlignment: CrossAxisAlignment.start,
                  children: const [
                    Text(
                      '🐾 About Pets Vibe',
                      style: TextStyle(
                        fontSize: 22,
                        fontWeight: FontWeight.bold,
                      ),
                    ),
                    SizedBox(height: 10),
                    Text(
                      'Pets Vibe is your all-in-one mobile companion for pet care. '
                      'From AI-powered disease detection to a complete pet e-commerce experience, '
                      'we aim to make pet ownership easier, more affordable, and joyful.',
                      style: TextStyle(fontSize: 16),
                    ),
                    SizedBox(height: 20),
                    Text(
                      'Version: 1.0.0',
                      style: TextStyle(
                        fontSize: 15,
                        fontWeight: FontWeight.bold,
                      ),
                    ),
                  ],
                ),
              ),
            ),

            const SizedBox(height: 20),

            // Team Credits Card
            Card(
              shape: RoundedRectangleBorder(
                borderRadius: BorderRadius.circular(16),
              ),
              elevation: 3,
              child: Padding(
                padding: const EdgeInsets.all(20.0),
                child: Column(
                  crossAxisAlignment: CrossAxisAlignment.start,
                  children: const [
                    Text(
                      '👥 Developed By',
                      style: TextStyle(
                        fontSize: 20,
                        fontWeight: FontWeight.bold,
                      ),
                    ),
                    Text('👨‍💻 Lead Developer: Dattatray Dharmoji'),
                    Text('🧑‍🏫 UI Design: Varad Pensalwar'),
                    Text('🤖 AI Integration: Sayali Patil'),
                    Text('🎨 Project Support: Gayatri Patkure'),
                    Text('👩‍🏫 Project Guided by: Mrs. Shweta Shete'),
                    Text('💬 Support and Feedback: Mrs. Shweta Shete'),
                    Text(
                      '🏫 Special Thanks: Sanjay Ghodawat University & Department Of AI & ML',
                      style: TextStyle(fontWeight: FontWeight.bold),
                    ),
                    SizedBox(height: 15),
                    Text(
                      'Built with Pets Vibe Team 🐾',
                      style: TextStyle(fontStyle: FontStyle.italic),
                    ),
                  ],
                ),
              ),
            ),

            const SizedBox(height: 20),

            // Footer Note
            Center(
              child: Text(
                '© 2025 Pets Vibe Team',
                style: TextStyle(
                  color: Colors.grey[700],
                  fontWeight: FontWeight.w500,
                ),
              ),
            ),
          ],
        ),
      ),
    );
  }
}
