class NGO {
  final int id;
  final String name;
  final String address;
  final String phoneNumber;

  NGO({
    required this.id,
    required this.name,
    required this.address,
    required this.phoneNumber,
  });

  factory NGO.fromJson(Map<String, dynamic> json) {
    return NGO(
      id: int.parse(json['id']),
      name: json['name'],
      address: json['address'],
      phoneNumber: json['phone_number'],
    );
  }
}
