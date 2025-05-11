import 'package:firebase_auth/firebase_auth.dart';
import 'package:flutter/material.dart';
import 'package:lottie/lottie.dart';
import 'package:pets_vibe/widget/app_drawer.dart';
import 'package:webview_flutter/webview_flutter.dart';

class HomeScreen extends StatefulWidget {
  const HomeScreen({super.key});

  @override
  _HomeScreenState createState() => _HomeScreenState();
}

class _HomeScreenState extends State<HomeScreen> {
  late final WebViewController _controller;
  bool isLoading = true;
  String currentUrl =
      "https://pets-vibe.infinityfreeapp.com/index.php"; // Default home page
  User? user;

  @override
  void initState() {
    super.initState();
    _controller =
        WebViewController()
          ..setJavaScriptMode(JavaScriptMode.unrestricted)
          ..setUserAgent(
            "Mozilla/5.0 (Linux; Android 10; Mobile) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.120 Mobile Safari/537.36",
          )
          ..setNavigationDelegate(
            NavigationDelegate(
              onPageStarted: (_) => setState(() => isLoading = true),
              onPageFinished: (_) => setState(() => isLoading = false),
            ),
          )
          ..loadRequest(Uri.parse(currentUrl)); // Load initial page

    // Get the current user information
    user = FirebaseAuth.instance.currentUser;
  }

  @override
  void dispose() {
    _controller.clearCache();
    super.dispose();
  }

  Future<bool> _onWillPop() async {
    if (await _controller.canGoBack()) {
      _controller.goBack();
      return false;
    }
    return true;
  }

  Future<void> _reloadWebView() async {
    await _controller.reload();
  }

  void _loadPage(String url) async {
    setState(() => isLoading = true);
    try {
      await _controller.loadRequest(Uri.parse(url));
      setState(() {
        currentUrl = url;
        isLoading = false;
      });
    } catch (e) {
      print("Error loading page: $e");
    }
  }

  String _getLottiePath() {
    final hour = DateTime.now().hour;
    if (hour < 12) {
      return 'assets/sunrise.json'; // Morning animation
    } else if (hour < 17) {
      return 'assets/sun.json'; // Afternoon animation
    } else {
      return 'assets/sunset.json'; // Evening animation
    }
  }

  String getGreeting() {
    final hour = DateTime.now().hour;
    if (hour < 12) {
      return "Good Morning ";
    } else if (hour < 17) {
      return "Good Afternoon ";
    } else {
      return "Good Evening ";
    }
  }

  @override
  Widget build(BuildContext context) {
    return WillPopScope(
      onWillPop: _onWillPop,
      child: Scaffold(
        drawer: AppDrawer(),

        appBar: AppBar(
          backgroundColor: Colors.white,
          elevation: 0,
          title: Row(
            children: [
              const SizedBox(width: 10),
              Text(
                getGreeting(),
                style: const TextStyle(
                  color: Colors.black,
                  fontSize: 20,
                  fontWeight: FontWeight.w600,
                ),
              ),
              SizedBox(
                height: 45,
                width: 45,
                child: Lottie.asset(
                  _getLottiePath(), // Dynamically load based on time
                  repeat: true,
                  fit: BoxFit.contain,
                ),
              ),
            ],
          ),
        ),

        body: Stack(children: [WebViewWidget(controller: _controller)]),
        bottomNavigationBar: BottomNavigationBar(
          backgroundColor: Colors.white,
          selectedItemColor: Colors.blue,
          unselectedItemColor: Colors.grey,
          currentIndex: _getCurrentIndex(),
          onTap: (index) {
            switch (index) {
              case 0:
                _loadPage("https://pets-vibe.infinityfreeapp.com/index.php");
                break;
              case 1:
                _loadPage(
                  "https://pets-vibe.infinityfreeapp.com/productlisting.php",
                );
                break;
              case 2:
                _loadPage(
                  "https://pets-vibe.infinityfreeapp.com/petadoption.php",
                );
                break;
              case 3:
                _loadPage("https://pets-vibe.infinityfreeapp.com/cart.php");
                break;
            }
          },
          items: [
            BottomNavigationBarItem(icon: Icon(Icons.home), label: "Home"),
            BottomNavigationBarItem(
              icon: Icon(Icons.shopping_bag),
              label: "Products",
            ),
            BottomNavigationBarItem(icon: Icon(Icons.pets), label: "Adopt Me"),
            BottomNavigationBarItem(
              icon: Icon(Icons.shopping_cart),
              label: "Cart",
            ),
          ],
        ),
      ),
    );
  }

  int _getCurrentIndex() {
    if (currentUrl.contains("productlisting")) return 1;
    if (currentUrl.contains("adopt")) return 2;
    if (currentUrl.contains("cart")) return 3;
    return 0;
  }
}
