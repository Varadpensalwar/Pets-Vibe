import 'package:flutter/material.dart';
import 'package:pets_vibe/models/ngo_model.dart';
import 'package:pets_vibe/widget/app_drawer.dart';
import 'package:pets_vibe/widget/ngo_card.dart';
import 'package:pets_vibe/data/ngo_data.dart'; // <-- Import your data

class NGOScreen extends StatelessWidget {
  const NGOScreen({super.key});

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      drawer: AppDrawer(),
      appBar: AppBar(title: Text("Connect with NGOs"), centerTitle: true),
      body: ListView.builder(
        itemCount: ngoList.length,
        itemBuilder: (context, index) {
          return NGOCard(
            ngo: ngoList[index],
            onSwiped: () {
              // Optional: Swipe logic
            },
          );
        },
      ),
    );
  }
}
