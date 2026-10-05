import os
import socket


def connect_ftp_server(host, port):
    control_socket = socket.socket(socket.AF_INET, socket.SOCK_STREAM)
    control_socket.connect((host, port))
    receive_response(control_socket)
    return control_socket


def send_command(control_socket, command):
    control_socket.sendall(command.encode("utf-8"))


def receive_response(control_socket):
    response = control_socket.recv(1024).decode("utf-8")
    print(response)
    return response


def open_data_connection(control_socket, data_socket=None):
    if data_socket is not None:
        data_socket.close()

    send_command(control_socket, "PASV\r\n")
    response = receive_response(control_socket)

    try:
        ip_and_port = [int(x) for x in response.split("(")[1].split(")")[0].split(",")]
        ip = ".".join(map(str, ip_and_port[:4]))
        port = ip_and_port[4] * 256 + ip_and_port[5]

        data_socket = socket.socket(socket.AF_INET, socket.SOCK_STREAM)
        data_socket.connect((ip, port))
        return data_socket

    except (IndexError, ValueError):
        print("Error parsing PASV response.")
        return None


def open_active_data_connection(control_socket, data_socket=None):
    if data_socket is not None:
        data_socket.close()

    data_socket = socket.socket(socket.AF_INET, socket.SOCK_STREAM)
    data_socket.bind(('vafrold2.beget.tech', 21))
    data_socket.listen(1)

    host, port = data_socket.getsockname()
    ip_parts = control_socket.getsockname()[0].split('.')
    port_command = f"{ip_parts[0]},{ip_parts[1]},{ip_parts[2]},{ip_parts[3]},{port // 256},{port % 256}"

    send_command(control_socket, f"PORT {port_command}\r\n")
    response = receive_response(control_socket)

    if not response.startswith('200'):
        print(f"Failed to set up active data connection: {response}")
        return None

    return data_socket


def list_files(control_socket, data_socket):
    if not data_socket:
        data_socket = open_active_data_connection(control_socket, data_socket)

    send_command(control_socket, "LIST\r\n")
    response = receive_response(control_socket)

    if not response.startswith('1'):
        print(f"Failed to initiate list command: {response}")
        return

    data = data_socket.recv(1024).decode("utf-8")
    print(data)

    data_socket.close()
    receive_response(control_socket)


def make_directory(control_socket, directory_name):
    send_command(control_socket, f"MKD {directory_name}\r\n")
    receive_response(control_socket)


def remove_directory(control_socket, directory_name):
    send_command(control_socket, f"RMD {directory_name}\r\n")
    receive_response(control_socket)


def change_directory(control_socket, directory_name):
    send_command(control_socket, f"CWD {directory_name}\r\n")
    receive_response(control_socket)


def store_file(control_socket, data_socket, filename):
    if not data_socket:
        data_socket = open_data_connection(control_socket)

    send_command(control_socket, f"STOR {filename}\r\n")
    response = receive_response(control_socket)

    if not response.startswith('1'):
        print(f"Failed to initiate STOR command: {response}")
        return

    try:
        with open(filename, "rb") as file:
            data = file.read(1024)
            while data:
                data_socket.sendall(data)
                data = file.read(1024)
    except FileNotFoundError:
        print(f"Error: File '{filename}' not found.")
    except socket.error as e:
        print(f"Error sending data: {e}")

    data_socket.close()
    receive_response(control_socket)

    print(f"{filename} uploaded successfully.")


def retrieve_file(control_socket, data_socket, filename):
    if not data_socket:
        data_socket = open_data_connection(control_socket)

    local_path = input("Enter local path to save the file: ")
    local_filename = os.path.join(local_path, filename)

    send_command(control_socket, f"RETR {filename}\r\n")
    response = receive_response(control_socket)

    if not response.startswith('1'):
        print(f"Failed to initiate RETR command: {response}")
        return

    with open(local_filename, "wb") as file:
        while True:
            data = data_socket.recv(1024)
            if not data:
                break
            file.write(data)

    data_socket.close()
    receive_response(control_socket)

    print(f"{filename} downloaded successfully to {local_filename}.")


def delete_file(control_socket, filename):
    send_command(control_socket, f"DELE {filename}\r\n")
    receive_response(control_socket)


def rename_file(control_socket, old_name, new_name):
    send_command(control_socket, f"RNFR {old_name}\r\n")
    receive_response(control_socket)

    send_command(control_socket, f"RNTO {new_name}\r\n")
    receive_response(control_socket)


def show_menu():
    print("FTP Client Menu:")
    print("1. List Files")
    print("2. Make Directory")
    print("3. Remove Directory")
    print("4. Change Directory")
    print("5. Upload File")
    print("6. Download File")
    print("7. Delete File")
    print("8. Rename File")
    print("0. Quit")


def execute_menu_choice(choice, control_socket, data_socket):

    if choice in ("1", "5", "6"):
        data_socket = open_data_connection(control_socket)

    if choice == "1":
        list_files(control_socket, data_socket)
    elif choice == "2":
        directory_name = input("Enter directory name to create: ")
        make_directory(control_socket, directory_name)
    elif choice == "3":
        directory_name = input("Enter directory name to remove: ")
        remove_directory(control_socket, directory_name)
    elif choice == "4":
        directory_name = input("Enter directory name to change to: ")
        change_directory(control_socket, directory_name)
    elif choice == "5":
        filename = input("Enter local file name to upload: ")
        store_file(control_socket, data_socket, filename)
    elif choice == "6":
        filename = input("Enter remote file name to download: ")
        retrieve_file(control_socket, data_socket, filename)
    elif choice == "7":
        filename = input("Enter file name to delete: ")
        delete_file(control_socket, filename)
    elif choice == "8":
        old_name = input("Enter current file name: ")
        new_name = input("Enter new file name: ")
        rename_file(control_socket, old_name, new_name)
    elif choice == "0":
        print("Exiting FTP client.")
    else:
        print("Invalid choice. Please enter a valid option.")

    if data_socket:
        data_socket.close()


def main():
    host = "vafrold2.beget.tech"
    port = 21

    control_socket = connect_ftp_server(host, port)

    send_command(control_socket, "USER vafrold2\r\n")
    receive_response(control_socket)
    send_command(control_socket, "PASS XthEVDsJy1sQ\r\n")
    receive_response(control_socket)
    send_command(control_socket, "TYPE I\r\n")
    receive_response(control_socket)

    data_socket = None

    while True:
        show_menu()
        choice = input("Enter your choice (0-8): ")
        execute_menu_choice(choice, control_socket, data_socket)
        if choice == "0":
            break

    control_socket.close()


if __name__ == "__main__":
    main()
