import 'package:flutter/material.dart';
import 'package:pets_vibe/models/ngo_model.dart';
import 'package:url_launcher/url_launcher.dart';

class NGOCard extends StatelessWidget {
  final NGO ngo;
  final VoidCallback onSwiped;

  const NGOCard({super.key, required this.ngo, required this.onSwiped});

  void _callNGO(String phone) async {
    final uri = Uri.parse("tel:$phone");
    if (await canLaunchUrl(uri)) {
      await launchUrl(uri);
    } else {
      throw "Could not launch $phone";
    }
  }

  void _messageNGO(String phone) async {
    final uri = Uri.parse("sms:$phone");
    if (await canLaunchUrl(uri)) {
      await launchUrl(uri);
    } else {
      throw "Could not send message to $phone";
    }
  }

  @override
  Widget build(BuildContext context) {
    return Card(
      margin: EdgeInsets.symmetric(horizontal: 16, vertical: 10),
      elevation: 5,
      shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(16)),
      child: Padding(
        padding: const EdgeInsets.all(16.0),
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            Text(
              ngo.name,
              style: TextStyle(fontSize: 20, fontWeight: FontWeight.bold),
            ),
            SizedBox(height: 8),
            Text(ngo.address, style: TextStyle(color: Colors.grey[700])),
            SizedBox(height: 12),
            Row(
              mainAxisAlignment: MainAxisAlignment.end,
              children: [
                ElevatedButton.icon(
                  onPressed: () => _callNGO(ngo.phoneNumber),
                  icon: Icon(Icons.call, color: Colors.white),
                  label: Text("Call", style: TextStyle(color: Colors.white)),
                  style: ElevatedButton.styleFrom(
                    backgroundColor: Colors.green,
                    shape: RoundedRectangleBorder(
                      borderRadius: BorderRadius.circular(10),
                    ),
                  ),
                ),
                SizedBox(width: 12),
                ElevatedButton.icon(
                  onPressed: () => _messageNGO(ngo.phoneNumber),
                  icon: Icon(Icons.message, color: Colors.white),
                  label: Text("Message", style: TextStyle(color: Colors.white)),
                  style: ElevatedButton.styleFrom(
                    backgroundColor: Colors.blue,
                    shape: RoundedRectangleBorder(
                      borderRadius: BorderRadius.circular(10),
                    ),
                  ),
                ),
              ],
            ),
          ],
        ),
      ),
    );
  }
}
