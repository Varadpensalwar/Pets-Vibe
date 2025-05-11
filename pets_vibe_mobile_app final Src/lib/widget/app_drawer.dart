import 'package:firebase_auth/firebase_auth.dart';
import 'package:flutter/material.dart';
import 'package:pets_vibe/screens/login_screen.dart';

class AppDrawer extends StatelessWidget {
  const AppDrawer({super.key});

  void _signOut(BuildContext context) async {
    await FirebaseAuth.instance.signOut();
    Navigator.pushReplacement(
      context,
      MaterialPageRoute(builder: (context) => LoginScreen()),
    );
  }

  @override
  Widget build(BuildContext context) {
    final user = FirebaseAuth.instance.currentUser;

    return Drawer(
      shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(30)),
      child: Column(
        children: [
          DrawerHeader(
            decoration: BoxDecoration(
              color: const Color.fromARGB(255, 237, 179, 102),
            ),
            child: SizedBox.expand(
              // 👈 Ensures it fills the space properly
              child: Column(
                mainAxisAlignment: MainAxisAlignment.center,
                crossAxisAlignment: CrossAxisAlignment.center,
                children: [
                  Image.asset(
                    'assets/pets_vibe_logo.png',
                    width: 90,
                    height: 90,
                  ),
                  SizedBox(height: 10),
                  Container(
                    padding: EdgeInsets.symmetric(horizontal: 8),
                    child: Text(
                      user != null
                          ? "👋 Hello, ${user.email!.split('@')[0]}"
                          : "👋 Hello, Guest",
                      style: TextStyle(
                        color: Colors.white,
                        fontSize: 18,
                        fontWeight: FontWeight.bold,
                      ),
                      overflow: TextOverflow.ellipsis,
                      maxLines: 1,
                    ),
                  ),
                ],
              ),
            ),
          ),

          Expanded(
            child: ListView(
              padding: EdgeInsets.zero,
              children: [
                ListTile(
                  leading: Icon(Icons.home),
                  title: Text('Home'),
                  onTap: () {
                    Navigator.pushNamed(context, '/home');
                  },
                ),
                ListTile(
                  leading: Icon(Icons.document_scanner_rounded),
                  title: Text('Self Diagnose'),
                  onTap: () {
                    Navigator.pushNamed(context, '/self-diagnose');
                  },
                ),
                ListTile(
                  leading: Icon(Icons.mood),
                  title: Text('Mood Detection'),
                  onTap: () {
                    Navigator.pushNamed(context, '/mood-detection');
                  },
                ),
                ListTile(
                  leading: Icon(Icons.people),
                  title: Text('NGO Calling'),
                  onTap: () {
                    Navigator.pushNamed(context, '/ngos');
                  },
                ),
                ListTile(
                  leading: Icon(Icons.call),
                  title: Text('Contact Us'),
                  onTap: () {
                    Navigator.pushNamed(context, '/contact');
                  },
                ),
                ListTile(
                  leading: Icon(Icons.info),
                  title: Text('About Us'),
                  onTap: () {
                    Navigator.pushNamed(context, '/about-us');
                  },
                ),
              ],
            ),
          ),

          Divider(),
          ListTile(
            leading: Icon(Icons.logout, color: Colors.red),
            title: Text('Log Out', style: TextStyle(color: Colors.red)),
            onTap: () => _signOut(context),
          ),
          SizedBox(height: 10),
        ],
      ),
    );
  }
}
