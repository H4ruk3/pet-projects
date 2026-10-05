import socket
import ssl
import base64


def send_email():
    smtp_server = "smtp.gmail.com"
    smtp_port = 465

    sender_email = "vafrolov73@gmail.com"
    sender_password = "grlr padu fzjp irlr"

    print("Введите адрес электронной почты получателя:")
    receiver_email = input()
    print("Введите тему письма:")
    subject = input()
    print("Введите текст сообщения:")
    message = input()

    context = ssl.create_default_context()
    with socket.socket(socket.AF_INET, socket.SOCK_STREAM) as client_socket:
        client_socket.connect((smtp_server, smtp_port))
        with context.wrap_socket(client_socket, server_hostname=smtp_server) as secure_client_socket:
            response = secure_client_socket.recv(1024).decode()
            if not response.startswith('220'):
                print("ERROR", response)
                return
            else:
                print(response)

            secure_client_socket.sendall(b"HELO smtp.gmail.com\r\n")
            response = secure_client_socket.recv(1024).decode()
            if not response.startswith('250'):
                print("ERROR", response)
                return
            print(response)

            secure_client_socket.sendall(b"AUTH LOGIN\r\n")
            response = secure_client_socket.recv(1024).decode()
            if not response.startswith('334'):
                print("ERROR", response)
                return
            print(response)

            username = base64.b64encode(sender_email.encode()).decode() + '\r\n'
            secure_client_socket.sendall(username.encode())
            response = secure_client_socket.recv(1024).decode()
            if not response.startswith('334'):
                print("ERROR", response)
                return
            print(response)

            password = base64.b64encode(sender_password.encode()).decode() + '\r\n'
            secure_client_socket.sendall(password.encode())
            response = secure_client_socket.recv(1024).decode()
            if not response.startswith('235'):
                print("ERROR", response)
                return
            print(response)

            secure_client_socket.sendall(f"MAIL FROM: <{sender_email}>\r\n".encode())
            response = secure_client_socket.recv(1024).decode()
            if not response.startswith('250'):
                print("ERROR", response)
                return
            print(response)

            secure_client_socket.sendall(f"RCPT TO: <{receiver_email}>\r\n".encode())
            response = secure_client_socket.recv(1024).decode()
            if not response.startswith('250'):
                print("ERROR", response)
                return
            print(response)

            secure_client_socket.sendall(b"DATA\r\n")
            response = secure_client_socket.recv(1024).decode()
            if not response.startswith('354'):
                print("ERROR", response)
                return
            print(response)

            email_message = f"From:{sender_email}\r\nTo:{receiver_email}\r\nSubject:{subject}\r\n\r\n{message}\r\n.\r\n"
            secure_client_socket.sendall(email_message.encode())
            response = secure_client_socket.recv(1024).decode()
            if not response.startswith('250'):
                print("ERROR", response)
                return
            print(response)

            secure_client_socket.sendall(b"QUIT \r\n")
            response = secure_client_socket.recv(1024).decode()
            if not response.startswith('221'):
                print("ERROR", response)
                return
            print(response)

        print("Электронное письмо успешно отправлено")


if __name__ == "__main__":
    send_email()
