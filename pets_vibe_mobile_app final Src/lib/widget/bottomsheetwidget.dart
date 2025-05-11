import 'package:flutter/material.dart';
import 'package:pets_vibe/data/remedies.dart';
import 'package:url_launcher/url_launcher.dart';

class DiseaseBottomSheetWidget extends StatelessWidget {
  final String disease;

  const DiseaseBottomSheetWidget({super.key, required this.disease});

  @override
  Widget build(BuildContext context) {
    String remedy =
        remedies[disease] ?? 'No remedy found. Please search online.';

    return SizedBox(
      // Wrap Container with SizedBox
      width: MediaQuery.of(context).size.width, // Set width to screen width
      child: Container(
        padding: const EdgeInsets.all(16),
        child: Column(
          mainAxisSize: MainAxisSize.min,
          children: [
            Text(
              disease.toUpperCase(),
              style: const TextStyle(fontSize: 20, fontWeight: FontWeight.bold),
            ),
            const SizedBox(height: 10),
            Text(remedy, style: const TextStyle(fontSize: 16)),
            const SizedBox(height: 20),
            ElevatedButton.icon(
              onPressed: () {
                _searchGoogle(disease);
              },
              icon: const Icon(
                Icons.search,
                color: Colors.white,
              ), // White icon for better visibility
              label: const Text(
                'Search on Google',
                style: TextStyle(
                  color: Colors.white,
                ), // White text for better visibility
              ),
              style: ElevatedButton.styleFrom(
                backgroundColor:
                    Colors.blue, // Use a primary color or your theme color
                foregroundColor: Colors.white, // Ensure text and icon are white
                padding: const EdgeInsets.symmetric(
                  horizontal: 20,
                  vertical: 12,
                ), // Add padding
                shape: RoundedRectangleBorder(
                  borderRadius: BorderRadius.circular(8), // Rounded corners
                ),
                elevation: 3, // Add a subtle shadow
                textStyle: const TextStyle(fontSize: 16), // Adjust text size
              ),
            ),
          ],
        ),
      ),
    );
  }

  Future<void> _searchGoogle(String disease) async {
    final Uri url = Uri.parse(
      'https://www.google.com/search?q=${disease.replaceAll(' ', '+')}+Home+Remedies+for+pets',
    );
    if (!await launchUrl(url, mode: LaunchMode.externalApplication)) {
      throw Exception('Could not launch $url');
    }
  }

  static void show(BuildContext context, String disease) {
    showModalBottomSheet(
      context: context,
      builder: (context) => DiseaseBottomSheetWidget(disease: disease),
    );
  }
}
